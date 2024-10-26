<?php
namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ReporteController extends Controller
{
    public function generarReporte($id)
    {
        // Título y datos dinámicos
        $cabecera = DB::select("
        SELECT CONCAT(x.seriecot, '-', x.numcot) AS cotizacion_numero, 
               x.cliente, 
               c.RazonSocial AS razon_social, 
               c.direccion , t.Deascripcion as moneda, t.Factor2 as simbolomoneda,
               e.Direccion,e.Url_Web,e.Email
        FROM docxs.cotizacioncab x inner join tablavarios t on t.cod=x.Moneda and t.clase='MON'
        inner join empresas e on e.Empresa=x.Empresa
        INNER JOIN cliente c ON c.ruc = x.Cliente  
        WHERE x.id = ?", [$id]);

    // Verificar si se ha encontrado la cotización
    if (empty($cabecera)) {
        return abort(404, "Cotización no encontrada.");
    }

    // Obtener la primera fila de la cabecera
    $cabecera = $cabecera[0];

    // Consulta SQL para el detalle de la cotización
    $detalles = DB::select("
        SELECT d.item, 
               d.CodProducto, 
               p.Descripcion, 
               d.umedida, 
               d.cantidad, 
               d.precio, 
               d.TotalPro 
        FROM docxs.cotizaciondet d 
        INNER JOIN productos p ON p.CodProducto = d.CodProducto 
        WHERE d.id = ?", [$id]);

    // Calcular totales
    $subtotal = array_sum(array_column($detalles, 'TotalPro'));
    $igv = $subtotal * 0.18;
    $total = $subtotal + $igv;

    // Preparar los datos para la vista
    $data = [
        'fecha' => now()->format('d/m/Y'),
        'numero' => $cabecera->cotizacion_numero,
        'ruc' => $cabecera->cliente,
        'cliente' => $cabecera->razon_social,
        'moneda' => $cabecera->moneda,
        'simbolomoneda' => $cabecera->simbolomoneda,
        'direccion' => $cabecera->direccion,
        'Direccion' => $cabecera->Direccion,
        'Url_Web' =>$cabecera-> Url_Web,
        'Email' =>$cabecera-> Email,
        'detalles' => $detalles,
        'subtotal' => $subtotal,        
        'igv' => $igv,
        'total' => $total,
    ];
        // Generar el PDF
        $pdf = Pdf::loadView('reportes.Reporte', $data);

        // Mostrar el PDF en una nueva pestaña
        return $pdf->stream('cotizacion-' . $id . '.pdf');
    }

    public function generarReportePedido($id)
    {
        // Título y datos dinámicos
        $cabecera = DB::select("
        SELECT CONCAT(x.serie, '-', x.numero) AS cotizacion_numero, 
               x.cliente, 
               c.RazonSocial AS razon_social, 
               c.direccion , t.Deascripcion as moneda, t.Factor2 as simbolomoneda,
               e.Direccion,e.Url_Web,e.Email
        FROM docxs.notapedidocab x inner join tablavarios t on t.cod=x.Moneda and t.clase='MON'
        inner join empresas e on e.Empresa=x.Empresa
        INNER JOIN cliente c ON c.ruc = x.Cliente  
        WHERE x.id = ?", [$id]);

    // Verificar si se ha encontrado la cotización
    if (empty($cabecera)) {
        return abort(404, "Pedido no encontrada.");
    }

    // Obtener la primera fila de la cabecera
    $cabecera = $cabecera[0];

    // Consulta SQL para el detalle de la cotización
    $detalles = DB::select("
        SELECT d.item, 
               d.Producto, 
               p.Descripcion, 
               d.umedida, 
               d.cantidad, 
               d.precioU, 
               d.Total_neto 
        FROM docxs.notapedidodetalle d 
        INNER JOIN productos p ON p.CodProducto = d.Producto 
        WHERE d.id = ?", [$id]);

    // Calcular totales
    $subtotal = array_sum(array_column($detalles, 'TotalPro'));
    $igv = $subtotal * 0.18;
    $total = $subtotal + $igv;

    // Preparar los datos para la vista
    $data = [
        'fecha' => now()->format('d/m/Y'),
        'numero' => $cabecera->cotizacion_numero,
        'ruc' => $cabecera->cliente,
        'cliente' => $cabecera->razon_social,
        'moneda' => $cabecera->moneda,
        'simbolomoneda' => $cabecera->simbolomoneda,
        'direccion' => $cabecera->direccion,
        'Direccion' => $cabecera->Direccion,
        'Url_Web' =>$cabecera-> Url_Web,
        'Email' =>$cabecera-> Email,
        'detalles' => $detalles,
        'subtotal' => $subtotal,        
        'igv' => $igv,
        'total' => $total,
    ];
        // Generar el PDF
        $pdf = Pdf::loadView('reportes.Reportepedido', $data);

        // Mostrar el PDF en una nueva pestaña
        return $pdf->stream('pedido-' . $id . '.pdf');
    }
}
