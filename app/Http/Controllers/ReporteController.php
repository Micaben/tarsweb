<?php
namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
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
            'Url_Web' => $cabecera->Url_Web,
            'Email' => $cabecera->Email,
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
            'Url_Web' => $cabecera->Url_Web,
            'Email' => $cabecera->Email,
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

    public function generarReporteGuia($id)
    {
        // Título y datos dinámicos
        $cabecera = DB::select("
        select a.*, e.RazonSocialEmpresa, e.direccion as dir, e.ubicacion,e.telefono, e.Ubigeo_emp, ifnull(ucli.Ubi_Departamento,'') as cli_Departamento, ifnull(ucli.Ubi_Provincia,'') as cli_Provincia,  ifnull(ucli.Ubi_Distrito,'') as cli_Distrito, ifnull(ini.Ubi_Departamento,'') as ini_Departamento,  ifnull(ini.Ubi_Provincia,'') as ini_Provincia, ifnull(ini.Ubi_Distrito,'') as ini_Distrito, ifnull(fin.Ubi_Departamento,'') as fin_Departamento, ifnull(fin.Ubi_Provincia,'') as fin_Provincia, ifnull(fin.Ubi_Distrito,'') as fin_Distrito 
        from GuiaRemision a 
        inner join Empresas e on a.Empresa=e.Empresa 
        left join UbigeoE ucli on a.grm_Delivery_DeliveryCustomerParty_Ubigeo = ucli.Ubi_Codigo 
        left join UbigeoE ini on a.grm_Shipment_OriginAddress_ID = ini.Ubi_Codigo 
        left join UbigeoE fin on a.grm_Shipment_DeliveryAddress_ID = fin.Ubi_Codigo 
        where a.id=?", [$id]);

        // Verificar si se ha encontrado la cotización
        if (empty($cabecera)) {
            return abort(404, "Guia no encontrada.");
        }

        // Obtener la primera fila de la cabecera
        $cabecera = $cabecera[0];

        $detalles = DB::select("
        SELECT d.grd_OrderLineReference, 
               d.grd_SellersItemIdentification_ID as Producto, 
               p.Descripcion, 
               d.grd_DeliveredQuantity_unitCode as umedida, 
               d.grd_DeliveredQuantity_DeliveredQuantity as cantidad, 
               d.precio_costo as precioU, 
               d.Total_neto 
        FROM docxs.guiaremisiond d 
        INNER JOIN productos p ON p.CodProducto = d.grd_SellersItemIdentification_ID 
        WHERE d.id = ?", [$id]);

        $empresa= session('empresa');
        $razonsocialempresa = $cabecera->RazonSocialEmpresa;
        $serie = $cabecera->grm_Serie;
        $numero = $cabecera->grm_Numero;
        $fecha = $cabecera->grm_Fecha;

        $qrCode = base64_encode(QrCode::format('png')->size(100)->generate($razonsocialempresa . '|' . $serie . '|' . $numero . '|' . $fecha));

        // Preparar los datos para la vista
        $data = [
            'razonsocialempresa' => $cabecera->RazonSocialEmpresa,
            'dir' => $cabecera->dir,
            'ubicacion' => $cabecera->ubicacion,
            'telefono' => $cabecera->telefono,
            'empresa' => $cabecera->empresa = session('empresa'),
            'fecha' => $cabecera->grm_Fecha,
            'serie' => $cabecera->grm_Serie,
            'numero' => $cabecera->grm_Numero,
            'ruc' => $cabecera->grm_Delivery_DeliveryCustomerParty_ID,
            'cliente' => $cabecera->grm_Delivery_DeliveryCustomerParty_Name,
            'motivo' => $cabecera->grm_Shipment_Information,
            'peso' => $cabecera->grm_Shipment_GrossWeightMeasure,
            'direccion' => $cabecera->grm_Delivery_DeliveryCustomerParty_Address,
            'direccionpartida' => $cabecera->grm_Shipment_OriginAddress_StreetName,
            'direccionllegada' => $cabecera->grm_Delivery_DeliveryCustomerParty_Address,
            'fechatraslado' => $cabecera->grm_Shipment_ShipmentStage_TransitPeriod,
            'ordencompra' => $cabecera->grm_NumeroOCompra,
            'razonsocialtransporte' => $cabecera->grm_Shipment_CarrierParty_Name,
            'ructransporte' => $cabecera->grm_Shipment_CarrierParty_ID,
            'nombreconductor' => $cabecera->grm_Shipment_ShipmentStage_DriverPerson_Name,
            'apellidoconductor' => $cabecera->grm_shipment_driverperson_familyname,
            'placa' => $cabecera->grm_Shipment_LicensePlateID,
            'dni' => $cabecera->grm_Shipment_DriverPerson_ID,
            'ciudad' => $cabecera->fin_Departamento,
            'licencia' => $cabecera->grm_DriverPerson_DocumentReference,
            'detalles' => $detalles,
            'qrCode' => $qrCode,
        ];


        // Generar el PDF
        $pdf = Pdf::loadView('reportes.Reporteguiaremision', $data);

        // Mostrar el PDF en una nueva pestaña
        return $pdf->stream($empresa.'-'.'09-'.$serie.'-'.$numero. '.pdf');
    }
}
