<?php

namespace App\Http\Controllers;

use App\Models\Movalmacencab;
use App\Models\Movalmacendet;
use App\Models\Cabmovpro;
use App\Repositories\Repositorios;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Log;
class MovalmacenController extends Controller
{

    public function mostrarMantenimiento()
    {
        return view('NotaIngreso');
    }

    public function guardardeNotaingreso(Request $request)
    {
        try {
            DB::beginTransaction();

            $request->validate([
                'fechaproceso' => 'required',
                'cboserie_text' => 'required',
                'numeronota' => 'required',
                'tipodocumento' => '',
            ]);

            $Iddoc = $request->input('iddeldocumento');
            $isNew = !$Iddoc || empty($Iddoc);

            // Insertar o actualizar Cabmovpro y Movalmacencab
            $cabmovproId = $this->guardarMovAlmacenCab($request, $isNew, $Iddoc);

            // Insertar o actualizar Movalmacendet
            $this->guardarMovalmacendet($request, $cabmovproId, $isNew, $Iddoc);

            DB::commit();
            return response()->json(['success' => true, 'id' => $cabmovproId]);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error durante la inserción de nota de ingreso: ' . $e->getMessage());
            \Log::error('Request data: ' . json_encode($request->all()));
            return response()->json(['success' => false, 'errors' => ['message' => $e->getMessage()]]);
        }
    }

    public function guardarMovAlmacenCab(Request $request, $isNew, $Iddoc)
    {
        $cabmovproId = null;
        if ($isNew) {
            // Insertar nuevo registro
            $cabmovpro = new Cabmovpro;
            $cabmovpro->empresa = session('empresa');
            $cabmovpro->kardex = '1';
            $cabmovpro->fecha = $request->input('fechaproceso');
            $cabmovpro->serie = $request->input('cboserie_text');
            $cabmovpro->numero = $request->input('numeronota');
            $cabmovpro->td = $request->input('tipodocumento');
            $cabmovpro->save();
            $cabmovproId = $cabmovpro->id;

            $ingreso = new Movalmacencab;
            $ingreso->id = $cabmovproId;
        } else {
            // Actualizar registro existente
            $cabmovpro = Cabmovpro::findOrFail($Iddoc);
            $cabmovpro->empresa = session('empresa');
            $cabmovpro->kardex = '1';
            $cabmovpro->fecha = $request->input('fechaproceso');
            $cabmovpro->serie = $request->input('cboserie');
            $cabmovpro->numero = $request->input('numeronota');
            $cabmovpro->td = $request->input('tipodocumento');
            $cabmovpro->save();
            $cabmovproId = $cabmovpro->id;

            $ingreso = Movalmacencab::where('id', $cabmovproId)->firstOrFail();
        }

        $ingreso->concepto = $request->input('cboconcepto');
        $ingreso->serie = $request->input('cboserie_text');
        $ingreso->numero = $request->input('numeronota');
        $ingreso->fecha = $request->input('fechaproceso');
        $ingreso->cliente = $request->input('proveedor');
        $ingreso->docref = $request->input('cbodocumentoasociado');
        $ingreso->numdocref = $request->input('documentoasociado');
        $ingreso->fe_compra = $request->input('fechacompra');
        $ingreso->tcambio = $request->input('tipocambio');
        $ingreso->moneda = $request->input('cbomoneda');
        $ingreso->ocompra = $request->input('ordencompra');
        $ingreso->guiaremision = $request->input('guiaremision');
        $ingreso->tipo_motivo = $request->input('cbotipooperacion');
        $ingreso->almacen = $request->input('cboalmacen');
        $ingreso->referencia = $request->input('comentarios');
        $ingreso->empresa = session('empresa');        
        $ingreso->td = $request->input('tipodocumento');
        $ingreso->kardex = '1';
        $ingreso->estado = 'P';
        $ingreso->tipo_motivo = 'I';
        $ingreso->transaccion = 'I';
        // Agregar otros campos según sea necesario
        $ingreso->save();

        return $cabmovproId;
    }

    public function guardarMovalmacendet(Request $request, $cabmovproId, $isNew, $Iddoc)
    {
        if (!$isNew) {
            // Restaurar stock y eliminar detalles existentes si es una actualización
            Repositorios::spRestoreStock($Iddoc);
            Repositorios::eliminaDet('Movalmacendet', $Iddoc);
        }

        $productos = json_decode($request->input('productos'), true);
        foreach ($productos as $producto) {
            $detalle = new Movalmacendet;
            
            $detalle->id = $cabmovproId;
            $detalle->tipo = 'I';
            $detalle->item = $producto['item'];
            $detalle->producto = $producto['codigo'];
            $detalle->nom_producto = $producto['descripcion'];
            $detalle->almacen = $producto['almacen'];
            $detalle->unidad = $producto['unidad'];
            $detalle->cantidad = $producto['cantidad'];
            $detalle->precio_costo = $producto['precio'];
            $detalle->total_bruto = $producto['total'];
            $detalle->total_neto = $producto['total'];
            $detalle->cantidad_uni = $producto['cantidad'];
            $detalle->tipoafectacionIGV = $producto['afectacion'];
            // Agregar otros campos según sea necesario
            $detalle->save();
        }

        // Actualizar el stock después de guardar los detalles
        $empresa = session('empresa'); // Obtener la empresa desde la sesión
        $serie = $request->input('cboserie_text'); // Obtener la serie desde el formulario
        $td = $request->input('tipodocumento');
        $idalmacen = $request->input('cboalmacen');
        $numero = $request->input('numeronota');
        $item = $detalle->item;
        $cproducto = $detalle->producto;
        $cantidad = $detalle->cantidad;
        Repositorios::spUpdateNumero($empresa, $td, $serie, $numero, 8);
        Repositorios::spUpdateSumStock($cabmovproId, $item, $empresa, $idalmacen, $cproducto, $cantidad, 0);

    }

    public function mostrarRegistrosIngreso(Request $request)
    {
        $mes = $request->query('mes');
        $anio = $request->query('anio');

        // Validar los parámetros
        $request->validate([
            'mes' => 'required|integer|between:1,12',
            'anio' => 'required|integer|min:2000|max:' . date('Y'),
        ]);

        $sql = "SELECT CONCAT(a.serie, '-', a.numero) AS Pedido,
                   DATE_FORMAT(fecha, '%d/%m/%Y') AS Fecha,
                   a.cliente,
                   CASE WHEN b.RazonSocial IS NULL THEN c.Nombres ELSE b.RazonSocial END AS nombre,
                   v.descripcion,
                   CONCAT(a.SerieDocRef, ' ', a.NumDocRef) AS Comprobante,
                   a.GuiaRemision,
                   a.UserCrea,
                   a.id
            FROM MovAlmacenCab a
            LEFT JOIN cliente b ON a.Cliente = b.ruc
            LEFT JOIN Proveedor c ON a.Cliente = c.Proveedor
            INNER JOIN AlmacenMov v ON v.id = a.Concepto
            LEFT JOIN tablavarios tv ON a.Moneda = tv.cod AND tv.clase = 'MON'
            WHERE td = '38'
              AND a.estado IN ('A', 'G', 'P')
              AND MONTH(Fecha) = ?
              AND YEAR(Fecha) = ?
            ORDER BY Fecha ASC";

        $resultados = DB::select($sql, [$mes, $anio]);

        $notaIngresos = array_map(function ($row) {
            return [
                'documento' => $row->Pedido,
                'fecha' => $row->Fecha,
                'ruc' => $row->cliente,
                'proveedor' => $row->nombre,
                'motivo' => $row->descripcion,
                'comprobante' => $row->Comprobante,
                'guia' => $row->GuiaRemision,
                'usuario' => $row->UserCrea,
                'id' => $row->id,
            ];
        }, $resultados);

        return response()->json($notaIngresos);
    }

    public function buscarregistroIngreso(Request $request, $id)
    {
        try {
            $query = "select a.serie,a.Numero , a.cliente, b.RazonSocial, a.Concepto, a.fecha, a.Condicion, a.OCompra, a.Referencia, 
                      case when a.sucursal is null then '' else a.sucursal end as Sucursal, a.direntrega, a.vendedor, a.Estado, a.valortotal, a.baseimp, a.igv, 
                      a.Moneda, m.Factor2, a.Total, a.usercrea, a.created_At, case when a.UserMod is null then '' else a.UserMod end as UserMod, 
                      case when a.updated_at is null then '' else a.updated_at end as FechaMod, a.codChofer, a.ubigeodest, a.ubigeoemisor, g.tra_Shipment_OriginAddress_AddressLine_Line, 
                      g.tra_Shipment_DeliveryAddress_AddressLine_Line, a.plazo, case when a.fechav is null then '' else a.Fechav end as fechaV, c.Descripcion, 
                      d.Descripcion, v.Nombres, m.factor3, np.Serie+'-'+np.Numero as Pedidos, es.Descripcion, a.id, 
                      a.in_igv, a.conretencion, gr.grm_Hash, gr.APISUNAT_NumTicket, gr.grm_EstadoRespuesta, gr.id_gr, g.tra_Shipment_ShipmentStage_DriverPerson_Name, 
                      g.tra_Shipment_DriverPerson_FamilyName, g.tra_Shipment_ShipmentStage_DriverPerson_schemeID, g.tra_Shipment_ShipmentStage_DriverPerson_ID, 
                      g.tra_Shipment_ShipmentStage_DriverPerson_Brevete, g.tra_Shipment_ShipmentStage_LicensePlateID, g.tra_Shipment_GrossWeightMeasure, 
                      g.tra_Shipment_TotalGoodsItemQuantity, g.tra_Shipment_ShipmentStage_CompanyID, g.tra_Shipment_ShipmentStage_RegistrationName, 
                      g.tra_Shipment_ShipmentStage_TransitPeriod, tc.Deascripcion, tdc.cod, m.Deascripcion, a.TD, a.TP_anexo, a.almacen, a.DocRef, 
                      a.seriedocref+'-'+a.Numdocref as numdocref, a.GuiaRemision, a.tcambio, Fe_compra, ww.Descripcion as conceptodes, ax.Deascripcion, am.Descripcion, dr.Descripcion 
                      from MovAlmacenCab a 
                      left join TablaVarios m on m.cod=a.Moneda and m.clase='MON' 
                      left join Cliente b on b.ruc=a.cliente 
                      left join GuiaRemision gr on gr.grm_Serie=a.Serie and gr.grm_Numero=a.Numero 
                      left join AlmacenMov c on c.id=a.concepto 
                      left join Documentos ww on ww.documento=a.td 
                      left join CondicionVenta d on d.id=a.condicion  
                      left join Vendedores v on v.id=a.Vendedor 
                      LEFT JOIN Estados ES ON es.Estado=a.Estado 
                      left join comprobantesf com on com.Almacen=a.Id
                      left join NotaPedidoCab np on np.IDPedido=a.IdPedido 
                      left join transporte_delivery g on a.Id=g.Id_Almacen 
                      left join TablaVarios tc on tc.cod=g.tra_Shipment_ShipmentStage_DriverPerson_schemeID and tc.clase='TD' 
                      left join TablaVarios tdc on tdc.cod=b.TipoD and tdc.clase='TD' 
                      left join TablaVarios ax on ax.cod=a.tp_anexo and ax.clase='AN' 
                      left join Almacen am on am.id=a.almacen and ax.clase='AN' 
                      left join documentos dr on dr.Documento=a.DocRef 
                      where a.id=?";

            $result = DB::select($query, [$id]);

            if (!empty($result)) {
                return response()->json(['success' => true, 'data' => $result[0]]);
            } else {
                return response()->json(['success' => false, 'message' => 'No se encontró ninguna guía con el ID proporcionado.']);
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error en la consulta de la guía: ' . $e->getMessage());
        }
    }

    public function buscardetalleIngreso($id)
    {
        try {
            $detalleGuia = MovAlmacenDet::selectRaw('item, n.producto, p.Descripcion, color.Descripcion AS color, u.Umedida, p.Ancho, n.Cantidad, n.Precio_Costo AS Precio, n.Total_neto AS Total, CASE WHEN n.detalle IS NULL THEN "" ELSE n.detalle END AS detalle')
                ->from('MovAlmacenDet as n')
                ->join('productos as p', 'p.codproducto', '=', 'n.producto')
                ->leftJoin('Color as color', 'color.Id', '=', 'p.IdColor')
                ->leftJoin('UnidadMed as u', 'u.Umedida', '=', 'p.IdUMedida')
                ->where('n.id', $id)
                ->orderBy('n.item')
                ->get();

            return response()->json(['detalleGuia' => $detalleGuia], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function anularRegistrosMovAlmacen($id)
    {
        $usuario = auth()->user()->name;  // Obtén el nombre del usuario autenticado
        try {
            Log::info('Iniciando anulación de registros para id: ' . $id);
            $mensaje = Repositorios::anularRegistros($id, $usuario);
            Log::info('Mensaje de repositorio: ' . $mensaje);
        } catch (\Exception $e) {
            Log::error('Error en anularRegistrosMovAlmacen: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
        return response()->json(['mensaje' => $mensaje], 200);
    }
}