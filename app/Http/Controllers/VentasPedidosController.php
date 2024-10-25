<?php

namespace App\Http\Controllers;

use App\Models\Pedidoscab;
use App\Models\identpedidos;
use App\Models\Pedidosdet;
use App\Repositories\Repositorios;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Log;

class VentasPedidosController extends Controller
{

    public function mostrarNotapedidoMantenimiento()
    {
        return view('Pedidos');
    }

    public function guardarPedidos(Request $request)
    {
        try {
            DB::beginTransaction();

            $request->validate([
                'fechaproceso' => 'required',
                'cboserie_text' => 'required',
                'numeronota' => 'required',
                'tipodocumento' => '',
            ]);

            $Iddoc = (int) $request->input('iddeldocumento'); // Aseguramos que el Iddoc sea un entero
            $isNew = !$Iddoc || empty($Iddoc);

            // Insertar o actualizar Cabmovpro y Movalmacencab
            $identpedidoId = $this->guardarPedidoscab($request, $isNew, $Iddoc);

            // Insertar o actualizar Movalmacendet
            $this->guardarPedidosdet($request, $identpedidoId, $isNew, $Iddoc);

            DB::commit();
            return response()->json(['success' => true, 'id' => $identpedidoId]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error durante la inserción de cotizacion: ' . $e->getMessage());
            Log::error('Request data: ' . json_encode($request->all()));
            return response()->json(['success' => false, 'errors' => ['message' => $e->getMessage()]]);
        }
    }

    public function guardarPedidoscab(Request $request, $isNew, $Iddoc)
    {
        $identpedidoId = null;
        try {
        if ($isNew) {
            // Insertar nuevo registro
            $identpedido = new Identpedidos();
            $identpedido->empresa = session('empresa');
            $identpedido->fecha = $request->input('fechaproceso');
            $identpedido->td = 'N1';
            $identpedido->save();
            $identpedidoId = $identpedido->id;

            $cotcab = new Pedidoscab;
            $cotcab->id = $identpedidoId;
        } else {
            // Actualizar registro existente
            $Identpedidos = Identpedidos::findOrFail($Iddoc);
            $Identpedidos->empresa = session('empresa');
            $Identpedidos->fecha = $request->input('fechaproceso');
            $Identpedidos->td = 'N1';
            $Identpedidos->save();
            $identpedidoId = $Identpedidos->id;

            $cotcab = Pedidoscab::where('id', $identpedidoId)->firstOrFail();
        }
        $cotcab->concepto = $request->input('cboconcepto');
        $cotcab->serie = $request->input('cboserie_text');
        $cotcab->numero = $request->input('numeronota');
        $cotcab->fecha = $request->input('fechaproceso');
        $cotcab->cliente = $request->input('proveedor');
        $cotcab->moneda = $request->input('cbomoneda');
        $cotcab->condicion = $request->input('cbocondicion');
        $cotcab->OCompra = $request->input('ordencompra');
        $cotcab->almacen = $request->input('cboalmacen');
        $cotcab->valortotal = $request->input('subtotal');
        $cotcab->baseimp = $request->input('subtotal');
        $cotcab->igv = $request->input('igv');
        $cotcab->in_igv = $request->has('inigv');
        $cotcab->total = $request->input('totalSuma');
        $cotcab->simbolomoneda = $request->input('lblmoneda');
        $cotcab->referencia = $request->input('comentarios');
        $cotcab->vendedor =  $request->input('cbovendedor');
        $cotcab->igv_porc =  '18.00';
        $cotcab->empresa = session('empresa');    
        $cotcab->usercre = session('nombre_usuario');        
        $cotcab->td = $request->input('tipodocumento');
        $cotcab->estado = 'P';

        $cotcab->save();

        return $identpedidoId;
    } catch (\Exception $e) {
        Log::error('Error durante la inserción de cotizacion: ' . $e->getMessage());
        return response()->json(['error' => 'Error durante la inserción de cotizacion'], 500);
    }
    }

    public function guardarPedidosdet(Request $request, $identpedidoId, $isNew, $Iddoc)
    {
        if (!$isNew) {
            Repositorios::eliminaDet('notapedidodetalle', $Iddoc);
        }

        $productos = json_decode($request->input('productos'), true);
        foreach ($productos as $producto) {
            $detalle = new Pedidosdet;
            
            $detalle->id = $identpedidoId;
            $detalle->item = $producto['item'];
            $detalle->producto = $producto['codigo'];
            $detalle->descripcion = $producto['descripcion'];
            $detalle->almacen = $producto['almacen'];
            $detalle->umedida = $producto['unidad'];
            $detalle->cantidad = $producto['cantidad'];
            $detalle->precioU = $producto['precio'];
            $detalle->total_igv = $producto['igv'];
            $detalle->total_sigv = $producto['totalsigv'];
            $detalle->total_neto = $producto['total'];  
            $detalle->total_cigv = $producto['precio'];            
            $detalle->porc_igv = '18';
            // Agregar otros campos según sea necesario
            $detalle->save();
        }

        // Actualizar el stock después de guardar los detalles
        $empresa = session('empresa'); // Obtener la empresa desde la sesión
        $serie = $request->input('cboserie_text'); // Obtener la serie desde el formulario
        $td = $request->input('tipodocumento');
        $numero = $request->input('numeronota');
        Repositorios::spUpdateNumero($empresa, $td, $serie, $numero, 8);
    }

    public function mostrarRegistrosPedidos(Request $request)
    {
        $mes = $request->query('mes');
        $anio = $request->query('anio');

        // Validar los parámetros
        $request->validate([
            'mes' => 'required|integer|between:1,12',
            'anio' => 'required|integer|min:2000|max:' . date('Y'),
        ]);

        $sql = "SELECT CONCAT(a.serie, '-', a.numero) AS Documento,
                   DATE_FORMAT(Fecha, '%d/%m/%Y') AS Fecha,
                   a.cliente,a.OCompra as oc,
                   b.RazonSocial,
                   a.UserCre, a.estado, T.factor2, a.total,
                   a.id
            FROM notapedidocab a
            LEFT JOIN cliente b ON a.Cliente = b.ruc
            left join tablavarios t on t.cod=a.moneda
            WHERE a.estado IN ('A', 'G', 'P')
              AND MONTH(Fecha) = ?
              AND YEAR(Fecha) = ?
               and t.clase='MON'
            ORDER BY Fecha ASC";

        $resultados = DB::select($sql, [$mes, $anio]);

        $notaIngresos = array_map(function ($row) {
            return [
                'documento' => $row->Documento,
                'fecha' => $row->Fecha,
                'ruc' => $row->cliente,
                'cliente' => $row->RazonSocial,
                'o/compra' => $row-> oc,
                'estado' => $row->estado,
                'mda' => $row->factor2,
                'total' => $row->total,                
                'id' => $row->id,
            ];
        }, $resultados);

        return response()->json($notaIngresos);
    }

    public function buscarregistroPedido(Request $request, $id)
    {
        try {
            $query = "select a.serie,a.numero,b.ruc,b.RazonSocial,a.Fecha,
            a.Concepto, a.Moneda,a.OCompra,a.Referencia, a.Estado,a.usercre,
            a.valortotal,a.baseimp,a.igv,tv.Factor2,a.Total,cv.descripcion,v.nombres,
            tv.Deascripcion,a.id,e.Descripcion,c.Descripcion,a.in_igv,dd.cod
            from notapedidocab a inner join Cliente b on b.ruc=a.Cliente
             left join ConceptoVenta c on c.id=a.concepto
             left join Condicionventa cv on cv.id=a.condicion
             left join vendedores v on v.id=a.vendedor 
             left join TablaVarios tv on a.Moneda=tv.cod and tv.clase ='MON' 
             left join TablaVarios dd on dd.cod=b.TipoD and dd.Clase='TD' 
             left join Estados e ON e.Estado = a.Estado where a.id=?";

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

    public function buscardetallePedido($id)
    {
        try {
            // Consulta para obtener los datos
            $detalleGuia = Pedidosdet::selectRaw('item, n.Producto as producto, p.Descripcion, color.Descripcion AS color, u.Umedida, p.Ancho, n.Cantidad, n.PrecioU as Precio, n.Total_neto as Totalpro, CASE WHEN n.detalle IS NULL THEN "" ELSE n.detalle END AS detalle')
                ->from('notapedidodetalle as n')
                ->join('productos as p', 'p.codproducto', '=', 'n.Producto')
                ->leftJoin('Color as color', 'color.Id', '=', 'p.IdColor')
                ->leftJoin('UnidadMed as u', 'u.Umedida', '=', 'p.IdUMedida')
                ->where('n.id', $id)
                ->orderBy('n.item')
                ->get();

            // Retornar los datos como JSON
            return response()->json(['detalleGuia' => $detalleGuia], 200);

        } catch (\Exception $e) {
            // Manejo de errores
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function anularRegistro($id)
    {
        try {
            // Actualiza el estado del documento en la tabla cotizacioncab
            DB::table('notapedidocab')
                ->where('id', $id)
                ->update(['estado' => 'A','valortotal' => '0.00','igv' => '0.00','total' => '0.00','baseimp'=>'0.00']);

            // Elimina los registros asociados en la tabla Pedidosdet
            DB::table('notapedidodetalle')
                ->where('id', $id)
                ->delete();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'No se pudo anular el documento'], 500);
        }
    }
}