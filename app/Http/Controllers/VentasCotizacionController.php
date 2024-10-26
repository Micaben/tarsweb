<?php

namespace App\Http\Controllers;

use App\Models\Cotizacioncab;
use App\Models\identpedidos;
use App\Models\cotizaciondet;
use App\Repositories\Repositorios;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Log;

class VentasCotizacionController extends Controller
{

    public function mostrarMantenimiento()
    {
        return view('Cotizaciones');
    }

    public function guardarCotizaciones(Request $request)
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
            $identpedidoId = $this->guardarCotizacionesCab($request, $isNew, $Iddoc);

            // Insertar o actualizar Movalmacendet
            $this->guardarCotizacionesdet($request, $identpedidoId, $isNew, $Iddoc);

            DB::commit();
            return response()->json(['success' => true, 'id' => $identpedidoId]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'errors' => ['message' => $e->getMessage()]]);
        }
    }

    public function guardarCotizacionesCab(Request $request, $isNew, $Iddoc)
    {
        $identpedidoId = null;

        if ($isNew) {
            // Insertar nuevo registro
            $identpedido = new Identpedidos();
            $identpedido->empresa = session('empresa');
            $identpedido->fecha = $request->input('fechaproceso');
            $identpedido->td = 'C1';
            $identpedido->save();
            $identpedidoId = $identpedido->id;

            $cotcab = new Cotizacioncab;
            $cotcab->id = $identpedidoId;
        } else {
            // Actualizar registro existente
            $Identpedidos = Identpedidos::findOrFail($Iddoc);
            $Identpedidos->empresa = session('empresa');
            $Identpedidos->fecha = $request->input('fechaproceso');
            $Identpedidos->td = 'C1';
            $Identpedidos->save();
            $identpedidoId = $Identpedidos->id;

            $cotcab = Cotizacioncab::where('id', $identpedidoId)->firstOrFail();
        }
        $cotcab->concepto = $request->input('cboconcepto');
        $cotcab->seriecot = $request->input('cboserie_text');
        $cotcab->numcot = $request->input('numeronota');
        $cotcab->fechacot = $request->input('fechaproceso');
        $cotcab->cliente = $request->input('proveedor');
        $cotcab->moneda = $request->input('cbomoneda');
        $cotcab->validez = $request->input('validez');
        $cotcab->OCompra = $request->input('ordencompra');
        $cotcab->almacen = $request->input('cboalmacen');
        $cotcab->valortotal = $request->input('subtotal');
        $cotcab->baseimp = $request->input('subtotal');
        $cotcab->igv = $request->input('igv');
        $cotcab->in_igv = $request->has('inigv');
        $cotcab->total = $request->input('totalSuma');
        $cotcab->factor = $request->input('lblmoneda');
        $cotcab->referencia = $request->input('comentarios');
        $cotcab->empresa = session('empresa');
        $cotcab->td = $request->input('tipodocumento');
        $cotcab->estado = 'P';

        $cotcab->save();

        return $identpedidoId;
    }

    public function guardarCotizacionesdet(Request $request, $identpedidoId, $isNew, $Iddoc)
    {
        if (!$isNew) {
            Repositorios::eliminaDet('Cotizaciondet', $Iddoc);
        }

        $productos = json_decode($request->input('productos'), true);
        foreach ($productos as $producto) {
            $detalle = new cotizaciondet;
            
            $detalle->id = $identpedidoId;
            $detalle->item = $producto['item'];
            $detalle->codproducto = $producto['codigo'];
            $detalle->descripcion = $producto['descripcion'];
            $detalle->almacen = $producto['almacen'];
            $detalle->umedida = $producto['unidad'];
            $detalle->cantidad = $producto['cantidad'];
            $detalle->precio = $producto['precio'];
            $detalle->totalpro = $producto['total'];
            $detalle->igvp = '18';
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

    public function mostrarRegistrosCotizaciones(Request $request)
    {
        $mes = $request->query('mes');
        $anio = $request->query('anio');

        // Validar los parámetros
        $request->validate([
            'mes' => 'required|integer|between:1,12',
            'anio' => 'required|integer|min:2000|max:' . date('Y'),
        ]);

        $sql = "SELECT CONCAT(a.seriecot, '-', a.numcot) AS Documento,
                   DATE_FORMAT(Fechacot, '%d/%m/%Y') AS Fecha,
                   a.cliente,
                   b.RazonSocial,
                   a.UserCre, a.estado, T.factor2, a.total,
                   a.id
            FROM cotizacioncab a
            LEFT JOIN cliente b ON a.Cliente = b.ruc
            left join tablavarios t on t.cod=a.moneda
            WHERE a.estado IN ('A', 'G', 'P')
              AND MONTH(Fechacot) = ?
              AND YEAR(Fechacot) = ?
               and t.clase='MON'
            ORDER BY Fechacot ASC";

        $resultados = DB::select($sql, [$mes, $anio]);

        $notaIngresos = array_map(function ($row) {
            return [
                'documento' => $row->Documento,
                'fecha' => $row->Fecha,
                'ruc' => $row->cliente,
                'cliente' => $row->RazonSocial,
                'estado' => $row->estado,
                'mda' => $row->factor2,
                'total' => $row->total,
                'usuario' => $row->UserCre,
                'id' => $row->id,
            ];
        }, $resultados);

        return response()->json($notaIngresos);
    }

    public function buscarregistroCotizacion(Request $request, $id)
    {
        try {
            $query = "select a.seriecot,a.numcot,b.ruc,b.RazonSocial,a.FechaCot,
            a.Concepto, a.Moneda,a.OCompra,a.Referencia, a.Estado,a.usercre,
            a.valortotal,a.baseimp,a.igv,tv.Factor2,a.Total,a.Validez,a.FechaCaduca,
            tv.Deascripcion,a.id,e.Descripcion,c.Descripcion,a.in_igv,dd.cod
            from Cotizacioncab a inner join Cliente b on b.ruc=a.Cliente
             left join ConceptoVenta c on c.id=a.concepto 
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

    public function buscardetalleCotizaciones($id)
    {
        try {
            // Consulta para obtener los datos
            $detalleGuia = cotizaciondet::selectRaw('item, n.codProducto as producto, p.Descripcion, color.Descripcion AS color, u.Umedida, p.Ancho, n.Cantidad, n.Precio, n.Totalpro, CASE WHEN n.detalle IS NULL THEN "" ELSE n.detalle END AS detalle')
                ->from('Cotizaciondet as n')
                ->join('productos as p', 'p.codproducto', '=', 'n.codProducto')
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

    public function anularRegistroCotizacion($id)
{
    Log::info('Llamada a anularRegistro con ID: ' . $id); // Log de depuración
    try {
        // Verifica si el ID existe
        $registro = DB::table('cotizacioncab')->where('id', $id)->first();
        if (!$registro) {
            Log::warning('ID no encontrado: ' . $id);
            return response()->json(['error' => 'Documento no encontrado'], 404);
        }

        // Actualiza el estado del documento en la tabla cotizacioncab
        DB::table('cotizacioncab')
            ->where('id', $id)
            ->update(['estado' => 'A', 'valortotal' => '0.00', 'igv' => '0.00', 'total' => '0.00', 'baseimp' => '0.00']);

        // Elimina los registros asociados en la tabla cotizaciondet
        DB::table('cotizaciondet')->where('id', $id)->delete();

        Log::info('Registro anulado exitosamente con ID: ' . $id); // Log de éxito
        return response()->json(['success' => true]);

    } catch (\Exception $e) {
        Log::error('Error al anular el registro: ' . $e->getMessage()); // Log de error
        return response()->json(['error' => 'No se pudo anular el documento'], 500);
    }
}
}