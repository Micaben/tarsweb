<?php

namespace App\Http\Controllers;

use App\Models\Cabmovpro;
use App\Models\Guiaremisioncab;
use App\Models\Guiaremisiondet;
use App\Models\TransporteDelivery;
use App\Repositories\Repositorios;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Log;
use DOMDocument;
use DateTime;
use Greenter\XMLSecLibs\Sunat\SignedXml;
use App\Services\ParseadorJDOM2;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class VentasGuiaremisionController extends Controller
{
    public $token = "";
    public $ticket = "";

    public function mostrarGuiaremisionMantenimiento()
    {
        return view(view: 'Guiaremision');
    }

    public function guardarGuiaremision(Request $request)
    {
        try {
            DB::beginTransaction();

            $request->validate([
                'fechaproceso' => 'required',
                'cboserie_text' => 'required',
                'numeronota' => 'required',
                'tipodocumento' => '',
            ]);
            $fecha = $request->input('fechaproceso');
            $serie = $request->input('cboserie_text');
            $numero = ltrim($request->input('numeronota'), 0);
            $Iddoc = (int) $request->input('iddeldocumento');
            $isNew = !$Iddoc || empty($Iddoc);

            $identpedidoId = $this->guardarGuiacab($request, $isNew, $Iddoc);
            $this->guardarGuiadet($request, $identpedidoId, $isNew, $Iddoc);
            $this->guardarTransporte($request, $identpedidoId, $isNew, $Iddoc);
            $xmlGeneradoCorrectamente = $this->CrearXMLGuiaRemision($identpedidoId);
            if ($xmlGeneradoCorrectamente) {
                $this->send($fecha, session('empresa'), '09', $serie, $numero, $identpedidoId);                
            }
            $this->generarReportePDFGuia($identpedidoId);                
            DB::commit();

            return response()->json(['success' => true, 'identpedidoId' => $identpedidoId]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error durante la inserción : ' . $e->getMessage());
            Log::error('Request data: ' . json_encode($request->all()));
            return response()->json(['success' => false, 'errors' => ['message' => $e->getMessage()]]);
        }
    }

    public function guardarGuiacab(Request $request, $isNew, $Iddoc)
    {
        $identpedidoId = null;
        try {
            if ($isNew) {
                // Insertar nuevo registro
                $identpedido = new Cabmovpro();
                $identpedido->empresa = session('empresa');
                $identpedido->kardex = '1';
                $identpedido->fecha = $request->input('fechaproceso');
                $identpedido->serie = $request->input('cboserie_text');
                $identpedido->numero = $request->input('numeronota');
                $identpedido->td = '09';
                $identpedido->save();
                $identpedidoId = $identpedido->id;

                $cotcab = new Guiaremisioncab;
                $cotcab->id = $identpedidoId;
            } else {
                // Actualizar registro existente
                $Identpedidos = Cabmovpro::findOrFail($Iddoc);
                $Identpedidos->empresa = session('empresa');
                $Identpedidos->fecha = $request->input('fechaproceso');
                $Identpedidos->td = '09';
                $Identpedidos->save();
                $identpedidoId = $Identpedidos->id;

                $cotcab = Guiaremisioncab::where('id', $identpedidoId)->firstOrFail();
            }
            $cotcab->Empresa = session('empresa');
            $cotcab->grm_TD = '09';
            $cotcab->grm_Serie = $request->input('cboserie_text');
            $cotcab->grm_Numero = ltrim($request->input('numeronota'), 0);
            $cotcab->grm_Fecha = $request->input('fechaproceso');
            $cotcab->grm_HoraEmision = date('H:i:s');
            $cotcab->grm_GRNumero = '';
            $cotcab->grm_GRTD = '';
            $cotcab->grm_GRTipoDoc = '';
            $cotcab->grm_DocumentStatusCode = 'Confirmado';
            $cotcab->grm_OrderReference_ID = $request->input('comentarios');
            $cotcab->grm_Delivery_DeliveryCustomerParty_ID = $request->input('proveedor');
            $cotcab->grm_Delivery_DeliveryCustomerParty_schemeID = '6';
            $cotcab->grm_Delivery_DeliveryCustomerParty_Name = $request->input('cboproveedor_text');
            $cotcab->grm_Delivery_DeliveryCustomerParty_Address = $request->input('direccionllegada');
            $cotcab->grm_Delivery_DeliveryCustomerParty_Ubigeo = $request->input('ubigeollegada');
            $cotcab->grm_Delivery_SellerSupplierParty_ID = '';
            $cotcab->grm_Delivery_SellerSupplierParty_schemeID = '';
            $cotcab->grm_Delivery_SellerSupplierParty_Name = '';
            $cotcab->grm_Shipment_HandlingCode = '01';
            $cotcab->grm_Shipment_Information = $request->input('cboconcepto_text');
            $cotcab->grm_Shipment_SplitConsignmentIndicator = 'false';
            $cotcab->grm_Shipment_GrossWeightMeasure = $request->input('peso');
            $cotcab->grm_Shipment_GrossWeightMeasure_unitCode = 'KGM';
            $cotcab->grm_Shipment_TotalTransportHandlingUnitQuantity = $request->input('bultos');
            $cotcab->grm_Shipment_ShipmentStage_TransportModeCode = $request->input('modalidad');
            $cotcab->grm_Shipment_ShipmentStage_TransitPeriod = $request->input('fechatraslado');
            $cotcab->grm_Shipment_CarrierParty_ID = $request->input('ructransporte');
            $cotcab->grm_Shipment_CarrierParty_schemeID = '6';
            $cotcab->grm_Shipment_CarrierParty_Name = $request->input('textoempresatr');
            $cotcab->grm_Shipment_LicensePlateID = $request->input('placa');
            $cotcab->grm_Shipment_DriverPerson_ID = $request->input('dni');
            $cotcab->grm_Shipment_DriverPerson_schemeID = $request->input('cbodocumento');
            $cotcab->grm_Shipment_ShipmentStage_DriverPerson_Name = $request->input('nombres');
            $cotcab->grm_Shipment_DeliveryAddress_ID = $request->input('ubigeollegada');
            $cotcab->grm_Shipment_DeliveryAddress_StreetName = $request->input('direccionpartida');
            $cotcab->grm_Shipment_TransportEquipment_ID = '1';
            $cotcab->grm_Shipment_OriginAddress_ID = $request->input('ubigeopartida');
            $cotcab->grm_Shipment_OriginAddress_StreetName = $request->input('direccionpartida');
            $cotcab->grm_Shipment_FirstArrivalPortLocation_ID = '1';
            $cotcab->grm_Hash = '';
            $cotcab->grm_Fec_Registro = date('Y-m-d H:i:s');
            $cotcab->grm_EstadoRespuesta = 'P';
            $cotcab->grm_MensajeRespuesta = '';
            $cotcab->grm_LineCountNumeric = '';
            $cotcab->grm_Shipment_ID = '';
            $cotcab->Grm_Estado = 'P';
            $cotcab->Grm_IdCorreoTransacc = 0;
            $cotcab->grm_NumeroOCompra = $request->input('ordencompra');
            $cotcab->grm_NumeroPedido = '';
            $cotcab->grm_DriverPerson_DocumentReference = $request->input('licencia');
            $cotcab->grm_Shipment_DriverPerson_FamilyName = $request->input('apellidos');
            $cotcab->grm_TransportHandlingUnit_AttachedTransportEquipment_ID = '';
            $cotcab->grm_TransportHandlingUnit_AttachedTransportEquipment_Document = '';
            $cotcab->grm_TransportHandlingUnit_RegistrationNationalityID = '';
            $cotcab->grm_SpecialInstructions_Trasbordo = '0';
            $cotcab->grm_SpecialInstructions_Retorno = '0';
            $cotcab->grm_SpecialInstructions_Embases = '0';
            $cotcab->grm_SpecialInstructions_M1 = '0';
            $cotcab->grm_Shipment_DeliveryAddress_AddressTypeCode = '';
            $cotcab->grm_Shipment_DespatchAddress_AddressTypeCode = '';
            $cotcab->grm_TransportHandlingUnit_Package_ID = '';
            $cotcab->grm_FirstArrivalPortLocationTypeCode = '0';
            $cotcab->grm_FirstArrivalPortLocationID = '';
            $cotcab->grm_FirstArrivalPortLocationName = '';
            $cotcab->grm_Booking = '';
            $cotcab->grm_PaisDestino = '';
            $cotcab->grm_NombreNave = '';
            $cotcab->grm_BuyerCustomerPartyID = '';
            $cotcab->grm_BuyerCustomerPartyNumber = '';
            $cotcab->grm_BuyerCustomerPartyName = '';
            $cotcab->valortotal = $request->input('subtotal');
            $cotcab->baseimp = $request->input('subtotal');
            $cotcab->igv = $request->input('igv');
            $cotcab->in_igv = $request->has('inigv');
            $cotcab->total = $request->input('totalSuma');
            $cotcab->in_igv = $request->input('cbomoneda');
            $cotcab->usercre = session('nombre_usuario');
            $cotcab->condicion = $request->input('cbocondicion');
            $cotcab->concepto = $request->input('cboconcepto');
            $cotcab->vendedor = $request->input('cbovendedor');
            $cotcab->moneda = $request->input('cbomoneda');
            $cotcab->almacen = $request->input('cboalmacen');
            $cotcab->razonSocial = session('razonsocialempresa');
            $cotcab->save();

            return $identpedidoId;
        } catch (\Exception $e) {
            Log::error('Error durante la inserción de cotizacion: ' . $e->getMessage());
            return response()->json(['error' => 'Error durante la inserción de cotizacion'], 500);
        }
    }

    public function guardarGuiadet(Request $request, $identpedidoId, $isNew, $Iddoc)
    {
        if (!$isNew) {
            Repositorios::eliminaDet('Guiaremisiond', $Iddoc);
        }

        $productos = json_decode($request->input('productos'), true);
        foreach ($productos as $producto) {
            $detalle = new Guiaremisiondet;

            $detalle->id = $identpedidoId;
            $detalle->grd_OrderLineReference = $producto['item'];
            $detalle->grd_SellersItemIdentification_ID = $producto['codigo'];
            $detalle->grd_SellersItemIdentification_Name = $producto['descripcion'];
            $detalle->almacen = $producto['almacen'];
            $detalle->grd_DeliveredQuantity_unitCode = $producto['unidad'];
            $detalle->grd_DeliveredQuantity_DeliveredQuantity = $producto['cantidad'];
            $detalle->precio_costo = $producto['precio'];
            $detalle->total_bruto = $producto['total'];
            $detalle->total_neto = $producto['total'];
            $detalle->porc_igv = '18';
            $detalle->guiaremisioncab_id = $identpedidoId;
            $detalle->save();
            $productosStock[] = [
                'item' => $producto['item'],
                'empresa' => session('empresa'),   // Supongo que 'empresa' es parte de la sesión
                'idalmacen' => $producto['almacen'],
                'producto' => $producto['codigo'],
                'cantidad' => $producto['cantidad'],
            ];
        }

        // Actualizar el stock después de guardar los detalles
        $empresa = session('empresa');
        $serie = $request->input('cboserie_text');
        $td = $request->input('tipodocumento');
        $numero = $request->input('numeronota');

        Repositorios::spUpdateNumero($empresa, $td, $serie, $numero, 8);
        Repositorios::upRestaStock($productosStock, $identpedidoId);
    }

    public function guardarTransporte(Request $request, $identpedidoId, $isNew, $Iddoc)
    {
        $request->validate([
            'peso' => 'required|numeric',
            'bultos' => 'required|integer',
            'placa' => 'required|string',
            'dni' => 'required|string',
            'licencia' => 'required|string',
            'nombres' => 'required|string',
            'apellidos' => 'required|string',
            'ubigeollegada' => 'required|string',
            'direccionllegada' => 'required|string',
            'ubigeopartida' => 'required|string',
            'direccionpartida' => 'required|string',
            'fechatraslado' => 'required|date',
        ]);
        try {
            //Log::info('Datos recibidos para transporte: ' . json_encode($request->all()));
            $detalle = new TransporteDelivery;
            $detalle->id_Almacen = $identpedidoId;
            $detalle->tra_Shipment_GrossWeightMeasure = $request->input('peso');
            $detalle->tra_Shipment_GrossWeight_unitCode = 'KG';
            $detalle->tra_Shipment_TotalGoodsItemQuantity = $request->input('bultos');
            $detalle->tra_Shipment_ShipmentStage_TransportModeCode = $request->input('modalidad');
            $detalle->tra_Shipment_ShipmentStage_CompanyID = $request->input('ructransporte');
            $detalle->tra_Shipment_ShipmentStage_schemeID = '6';
            $detalle->tra_Shipment_ShipmentStage_RegistrationName = $request->input('textoempresatr');
            $detalle->tra_Shipment_ShipmentStage_LicensePlateID = $request->input('placa');
            $detalle->tra_Shipment_ShipmentStage_DriverPerson_ID = $request->input('dni');
            $detalle->tra_Shipment_ShipmentStage_DriverPerson_schemeID = $request->input('cbodocumento');
            $detalle->tra_Shipment_ShipmentStage_DriverPerson_Brevete = $request->input('licencia');
            $detalle->tra_Shipment_ShipmentStage_DriverPerson_Name = $request->input('nombres');
            $detalle->tra_Shipment_DriverPerson_FamilyName = $request->input('apellidos');
            $detalle->unidad = $request->input('unidad');
            $detalle->tra_Shipment_DeliveryAddress_CountrySubentityCode = $request->input('ubigeollegada');
            $detalle->tra_Shipment_DeliveryAddress_AddressLine_Line = $request->input('direccionllegada');
            $detalle->tra_Shipment_OriginAddress_CountrySubentityCode = $request->input('ubigeopartida');
            $detalle->tra_Shipment_OriginAddress_AddressLine_Line = $request->input('direccionpartida');
            $detalle->tra_Shipment_ShipmentStage_TransitPeriod = $request->input('fechatraslado');
            $detalle->save();

        } catch (\Exception $e) {
            Log::error('Error al guardar el transporte: ' . $e->getMessage());
            return response()->json(['error' => 'Error al guardar el transporte: ' . $e->getMessage()], 500);
        }
    }

    public function mostrarRegistrosGuia(Request $request)
    {
        $mes = $request->query('mes');
        $anio = $request->query('anio');

        $request->validate([
            'mes' => 'required|integer|between:1,12',
            'anio' => 'required|integer|min:2000|max:' . date('Y'),
        ]);

        $sql = "SELECT CONCAT(a.grm_serie, '-', a.grm_numero) AS Documento,
                   DATE_FORMAT(grm_Fecha, '%d/%m/%Y') AS Fecha,
                   a.grm_Delivery_DeliveryCustomerParty_ID,a.grm_numeroOCompra as oc,
                   b.RazonSocial,
                   a.UserCre, a.grm_estado, T.factor2, a.total,
                   a.id
            FROM guiaremision a
            LEFT JOIN cliente b ON a.grm_Delivery_DeliveryCustomerParty_ID = b.ruc
            left join tablavarios t on t.cod=a.moneda
            WHERE a.grm_estado IN ('A', 'G', 'P')
              AND MONTH(grm_Fecha) = ?
              AND YEAR(grm_Fecha) = ?
               and t.clase='MON'
            ORDER BY grm_Fecha ASC";

        $resultados = DB::select($sql, [$mes, $anio]);

        $notaIngresos = array_map(function ($row) {
            return [
                'documento' => $row->Documento,
                'fecha' => $row->Fecha,
                'ruc' => $row->grm_Delivery_DeliveryCustomerParty_ID,
                'cliente' => $row->RazonSocial,
                'o/compra' => $row->oc,
                'estado' => $row->grm_estado,
                'mda' => $row->factor2,
                'total' => $row->total,
                'id' => $row->id,
            ];
        }, $resultados);

        return response()->json($notaIngresos);
    }

    public function buscarregistroGuia(Request $request, $id)
    {
        try {
            $query = "select a.grm_serie,a.grm_numero,b.ruc,b.RazonSocial,a.grm_Fecha,
            a.Concepto, a.Moneda, a.grm_numeroOCompra,a.grm_OrderReference_ID, a.grm_Estado as Estado,a.usercre,
            a.valortotal,a.baseimp,a.igv,tv.Factor2,a.Total,cv.descripcion,v.nombres,
            tv.Deascripcion,a.id,e.Descripcion,c.Descripcion,a.in_igv,dd.cod
            from guiaremision a inner join Cliente b on b.ruc=a.grm_Delivery_DeliveryCustomerParty_ID
             left join ConceptoVenta c on c.id=a.concepto
             left join Condicionventa cv on cv.id=a.condicion
             left join vendedores v on v.id=a.vendedor 
             left join TablaVarios tv on a.Moneda=tv.cod and tv.clase ='MON' 
             left join TablaVarios dd on dd.cod=b.TipoD and dd.Clase='TD' 
             left join Estados e ON e.Estado = a.grm_estado where a.id=?";
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

    public function buscardetalleGuia($id)
    {
        try {
            $detalleGuia = Guiaremisiondet::selectRaw('n.grd_OrderLineReference as item, n.grd_SellersItemIdentification_ID as producto, p.Descripcion, color.Descripcion AS color, u.Umedida, p.Ancho, n.grd_DeliveredQuantity_DeliveredQuantity as Cantidad, n.precio_costo as Precio, n.Total_neto as Totalpro, CASE WHEN n.detalle IS NULL THEN "" ELSE n.detalle END AS detalle')
                ->from('guiaremisiond as n')
                ->join('productos as p', 'p.codproducto', '=', 'n.grd_SellersItemIdentification_ID')
                ->leftJoin('Color as color', 'color.Id', '=', 'p.IdColor')
                ->leftJoin('UnidadMed as u', 'u.Umedida', '=', 'p.IdUMedida')
                ->where('n.id', $id)
                ->orderBy('n.grd_OrderLineReference')
                ->get();
            return response()->json(['detalleGuia' => $detalleGuia], 200);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function anularRegistroGuia($id)
    {
        try {
            $table = 'guiaremisiond';
            $tablejoin = 'guiaremision';
            $columns = [
                'item' => 'grd_OrderLineReference',
                'producto' => 'grd_SellersItemIdentification_ID',
                'cantidad' => 'grd_DeliveredQuantity_DeliveredQuantity',
                'almacen' => 'almacen',
                'empresa' => 'empresa'
            ];
            Repositorios::spRestoreStock($id, $table, $columns, $tablejoin, false); //cantidad en positvo
            // Actualiza el estado del documento en la tabla cotizacioncab
            DB::table('guiaremision')
                ->where('id', $id)
                ->update(['grm_estado' => 'A', 'valortotal' => '0.00', 'igv' => '0.00', 'total' => '0.00', 'baseimp' => '0.00']);

            // Elimina los registros asociados en la tabla Pedidosdet
            DB::table('guiaremisiond')
                ->where('id', $id)
                ->delete();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'No se pudo anular el documento'], 500);
        }
    }

    public function buscarregistroTransportista($id)
    {
        $datos = TransporteDelivery::where('id_almacen', $id)->first();
        if ($datos) {
            return response()->json($datos);
        } else {
            \Log::info('No se encontraron datos para el RUC: ' . $id);
        }
    }

    function CrearXMLGuiaRemision($idGuia): bool
    {
        $guiaRemisionObj = DB::table('guiaremision')
            ->where('id', operator: $idGuia)
            ->first();

        $detalles = DB::table('guiaremisiond')
            ->join('productos', 'guiaremisiond.grd_SellersItemIdentification_ID', '=', 'productos.CodProducto')
            ->where('guiaremisiond.id', $idGuia)
            ->select('guiaremisiond.*', 'productos.CodSunat')
            ->get();

        $numeroItems = $detalles->count();
        if (!$guiaRemisionObj || $detalles->isEmpty()) {
            throw new \Exception("No se encontró la guía de remisión con ID: $idGuia o no tiene detalles.");
        }
        $doc = new DOMDocument();
        $doc->formatOutput = FALSE;
        $doc->preserveWhiteSpace = TRUE;
        $doc->encoding = 'utf-8';

        $xml = '<?xml version="1.0" encoding="UTF-8"?>
        <DespatchAdvice xmlns="urn:oasis:names:specification:ubl:schema:xsd:DespatchAdvice-2" xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2" 
        xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2" xmlns:ccts="urn:un:unece:uncefact:documentation:2" 
        xmlns:ds="http://www.w3.org/2000/09/xmldsig#" xmlns:ext="urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2" 
        xmlns:qdt="urn:oasis:names:specification:ubl:schema:xsd:QualifiedDatatypes-2" xmlns:udt="urn:un:unece:uncefact:data:specification:UnqualifiedDataTypesSchemaModule:2" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
        <ext:UBLExtensions>
		    <ext:UBLExtension>
			    <ext:ExtensionContent></ext:ExtensionContent>
		    </ext:UBLExtension>
	    </ext:UBLExtensions>
        <cbc:UBLVersionID>2.1</cbc:UBLVersionID>
            <cbc:CustomizationID>2.0</cbc:CustomizationID>
	            <cbc:ID>' . $guiaRemisionObj->grm_Serie . '-' . $guiaRemisionObj->grm_Numero . '</cbc:ID>
	                <cbc:IssueDate>' . $guiaRemisionObj->grm_Fecha . '</cbc:IssueDate>
	                <cbc:IssueTime>' . $guiaRemisionObj->grm_HoraEmision . '</cbc:IssueTime>
                        <cbc:DespatchAdviceTypeCode listAgencyName="PE:SUNAT" listName="Tipo de Documento" listSchemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo51"
                            listURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo01">' . $guiaRemisionObj->grm_TD . '</cbc:DespatchAdviceTypeCode>
                                <cbc:LineCountNumeric>' . $numeroItems . '</cbc:LineCountNumeric>';

        $xml .= '<cac:Signature>
                                        <cbc:ID>S' . $guiaRemisionObj->grm_Serie . '-' . $guiaRemisionObj->grm_Numero . '</cbc:ID>
                                            <cac:SignatoryParty>
                                                <cac:PartyIdentification>
                                                    <cbc:ID>' . $guiaRemisionObj->Empresa . '</cbc:ID>
                                                </cac:PartyIdentification>
                                                    <cac:PartyName>
                                                        <cbc:Name><![CDATA[' . $guiaRemisionObj->razonSocial . ']]></cbc:Name>
                                                    </cac:PartyName>
                                            </cac:SignatoryParty>
                                                <cac:DigitalSignatureAttachment>
                                                    <cac:ExternalReference>
                                                        <cbc:URI>S' . $guiaRemisionObj->grm_Serie . '-' . $guiaRemisionObj->grm_Numero . '</cbc:URI>
                                                    </cac:ExternalReference>
                                                </cac:DigitalSignatureAttachment>
                                    </cac:Signature>';

        $xml .= '<cac:DespatchSupplierParty>
                <cbc:CustomerAssignedAccountID schemeID="6">' . $guiaRemisionObj->Empresa . '</cbc:CustomerAssignedAccountID>		
		<cac:Party>
                <cac:PartyIdentification>
                    <cbc:ID schemeAgencyName="PE:SUNAT" schemeID="6" schemeName="Documento de Identidad" schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo06">' . $guiaRemisionObj->Empresa . '</cbc:ID>
                </cac:PartyIdentification>
                <cac:PartyName>
                    <cbc:Name><![CDATA[' . $guiaRemisionObj->razonSocial . ']]></cbc:Name>
                </cac:PartyName>
            <cac:PartyLegalEntity>
                <cbc:RegistrationName>
                    <![CDATA[' . $guiaRemisionObj->razonSocial . ']]>
                </cbc:RegistrationName>
            </cac:PartyLegalEntity>
        </cac:Party>
	    </cac:DespatchSupplierParty>';

        $xml .= '<cac:DeliveryCustomerParty>		
		<cac:Party>
            <cac:PartyIdentification>
                <cbc:ID schemeAgencyName="PE:SUNAT" schemeID="' . $guiaRemisionObj->grm_Delivery_DeliveryCustomerParty_schemeID . '" schemeName="Documento de Identidad"  schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo06">' . $guiaRemisionObj->grm_Delivery_DeliveryCustomerParty_ID . '</cbc:ID>
            </cac:PartyIdentification>
			<cac:PartyLegalEntity>
				<cbc:RegistrationName>' . $guiaRemisionObj->grm_Delivery_DeliveryCustomerParty_Name . '></cbc:RegistrationName>
			</cac:PartyLegalEntity>
		</cac:Party>
	</cac:DeliveryCustomerParty>';

        $xml .= '<cac:Shipment>
            <cbc:ID>1</cbc:ID>
                <cbc:HandlingCode>' . $guiaRemisionObj->grm_Shipment_HandlingCode . '</cbc:HandlingCode>
                    <cbc:HandlingInstructions>' . $guiaRemisionObj->grm_Shipment_Information . '</cbc:HandlingInstructions>
                        <cbc:GrossWeightMeasure unitCode="' . $guiaRemisionObj->grm_Shipment_GrossWeightMeasure_unitCode . '">' . $guiaRemisionObj->grm_Shipment_GrossWeightMeasure . '</cbc:GrossWeightMeasure>
                            <cbc:SplitConsignmentIndicator>' . $guiaRemisionObj->grm_Shipment_SplitConsignmentIndicator . '</cbc:SplitConsignmentIndicator>
            <cac:ShipmentStage>
                <cbc:TransportModeCode listAgencyName="PE:SUNAT" listName="Modalidad de traslado" listURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo18">' . $guiaRemisionObj->grm_Shipment_ShipmentStage_TransportModeCode . '</cbc:TransportModeCode>
                    <cac:TransitPeriod>
                        <cbc:StartDate>' . $guiaRemisionObj->grm_Shipment_ShipmentStage_TransitPeriod . '</cbc:StartDate>
                    </cac:TransitPeriod>';

        if ($guiaRemisionObj->grm_Shipment_ShipmentStage_TransportModeCode == '01'):
            $xml .= '<cac:CarrierParty>
                        <cac:PartyIdentification>
                            <cbc:ID schemeID="' . $guiaRemisionObj->grm_Delivery_DeliveryCustomerParty_schemeID . '">' . $guiaRemisionObj->grm_Shipment_DriverPerson_ID . '</cbc:ID>
                        </cac:PartyIdentification>
                    <cac:PartyLegalEntity>
                        <cbc:RegistrationName>
                            <![CDATA[' . $guiaRemisionObj->grm_Shipment_CarrierParty_Name . ']]>
                        </cbc:RegistrationName>
                        <cbc:CompanyID>0001</cbc:CompanyID>
                    </cac:PartyLegalEntity>
                    </cac:CarrierParty>';
        endif;

        if ($guiaRemisionObj->grm_Shipment_ShipmentStage_TransportModeCode == '02'):
            $xml .= '<cac:DriverPerson>
                        <cbc:ID schemeAgencyName="PE:SUNAT" schemeID="' . $guiaRemisionObj->grm_Shipment_DriverPerson_schemeID . '" schemeName="Documento de Identidad" schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo06">' . $guiaRemisionObj->grm_Shipment_DriverPerson_ID . '</cbc:ID>
                        <cbc:FirstName>' . $guiaRemisionObj->grm_Shipment_ShipmentStage_DriverPerson_Name . '</cbc:FirstName>
                        <cbc:FamilyName>' . $guiaRemisionObj->grm_shipment_driverperson_familyname . '</cbc:FamilyName>
                        <cbc:JobTitle>Principal</cbc:JobTitle>
                    <cac:IdentityDocumentReference>
                        <cbc:ID>' . $guiaRemisionObj->grm_DriverPerson_DocumentReference . '</cbc:ID>
                    </cac:IdentityDocumentReference>
                    </cac:DriverPerson>';
        endif;
        $xml .= '</cac:ShipmentStage>';

        $xml .= '<cac:Delivery>
                <cac:DeliveryAddress>
                    <cbc:ID schemeAgencyName="PE:INEI" schemeName="Ubigeos">' . $guiaRemisionObj->grm_Delivery_DeliveryCustomerParty_Ubigeo . '</cbc:ID>
                    <cac:AddressLine>
                        <cbc:Line>' . $guiaRemisionObj->grm_Delivery_DeliveryCustomerParty_Address . '</cbc:Line>
                    </cac:AddressLine>
                </cac:DeliveryAddress>
                <cac:Despatch>
                    <cac:DespatchAddress>
                        <cbc:ID schemeAgencyName="PE:INEI" schemeName="Ubigeos">' . $guiaRemisionObj->grm_Shipment_OriginAddress_ID . '</cbc:ID>
                            <cac:AddressLine>
                                <cbc:Line>' . $guiaRemisionObj->grm_Shipment_DeliveryAddress_StreetName . '</cbc:Line>
                            </cac:AddressLine>
                    </cac:DespatchAddress>
                </cac:Despatch>
        </cac:Delivery>';

        $xml .= '<cac:TransportHandlingUnit>
                <cac:TransportEquipment>
                    <cbc:ID>' . $guiaRemisionObj->grm_Shipment_LicensePlateID . '</cbc:ID>        
                </cac:TransportEquipment>
        </cac:TransportHandlingUnit>';

        $xml .= '<cac:OriginAddress>
                <cbc:ID schemeAgencyName="PE:INEI" schemeName="Ubigeos">' . $guiaRemisionObj->grm_Shipment_OriginAddress_ID . '</cbc:ID>
                    <cac:AddressLine>
                        <cbc:Line>' . $guiaRemisionObj->grm_Shipment_DeliveryAddress_StreetName . '</cbc:Line>
                    </cac:AddressLine>
        </cac:OriginAddress>
                <cac:FirstArrivalPortLocation>
                    <cbc:ID>1</cbc:ID>
                </cac:FirstArrivalPortLocation>';

        $xml .= '</cac:Shipment>';

        foreach ($detalles as $v) {
            $xml .= '<cac:DespatchLine>
                <cbc:ID>' . $v->grd_OrderLineReference . '</cbc:ID>
                <cbc:DeliveredQuantity unitCode="' . $v->grd_DeliveredQuantity_unitCode . '">' . $v->grd_DeliveredQuantity_DeliveredQuantity . '</cbc:DeliveredQuantity>
                <cac:OrderLineReference>
                    <cbc:LineID>' . $v->grd_OrderLineReference . '</cbc:LineID>
                </cac:OrderLineReference>
                <cac:Item>
                    <cbc:Description><![CDATA[' . $v->grd_SellersItemIdentification_Name . ']]></cbc:Description>
                    <cac:SellersItemIdentification>
                        <cbc:ID>' . $v->grd_SellersItemIdentification_ID . '</cbc:ID>
                    </cac:SellersItemIdentification>';

            if (!empty($v->CodSunat)) {
                $xml .= '<cac:CommodityClassification>
                    <cbc:ItemClassificationCode listID="UNSPSC" listAgencyName="GS1 US" listName="Item Classification">' . $v->CodSunat . '</cbc:ItemClassificationCode>
                </cac:CommodityClassification>';
            }

            $xml .= '</cac:Item>
            </cac:DespatchLine>';
        }
        $xml .= '</DespatchAdvice>';
        $certificado = storage_path('certificates/certificate.pem');
        //$certPassword = 'userfact2022';
        //file_put_contents(storage_path('app/xml/antes_firma.xml'), $xml);
        //$xmlFirmado = $this->firmarXML($xml, $certificado, $certPassword);
        $nombreArchivoXML = session('empresa') . '-' . $guiaRemisionObj->grm_TD . '-' . $guiaRemisionObj->grm_Serie . '-' . $guiaRemisionObj->grm_Numero . '.xml';
        $fechaActual = date('Ymd');
        $rutaCarpetaXML = public_path('/storage/XML/' . $fechaActual);

        // Verificar si la carpeta existe, si no, crearla
        if (!is_dir($rutaCarpetaXML)) {
            mkdir($rutaCarpetaXML, 0777, true); // Crear la carpeta con permisos 0777
        }
        $rutaCompletaXML = $rutaCarpetaXML . '/' . $nombreArchivoXML;
        // Guardar el XML sin firmar
        file_put_contents($rutaCompletaXML, $xml);

        if (!file_exists($rutaCompletaXML)) {
            throw new \Exception("El archivo XML no se guardó correctamente en la ruta: " . $rutaCompletaXML);
        }
        try {
            if (!file_exists($rutaCompletaXML)) {
                throw new \Exception("El archivo XML no existe en la ruta especificada.");
            }

            // Instanciar la clase de firma y cargar el certificado
            $signer = new SignedXml();
            $signer->setCertificateFromFile($certificado);

            // Firmar el archivo XML
            $xmlSigned = $signer->signFromFile($rutaCompletaXML);

            // Guardar el XML firmado
            file_put_contents($rutaCompletaXML, $xmlSigned);

            // Verificar que el XML firmado se guardó correctamente
            if (!file_exists($rutaCompletaXML)) {
                throw new \Exception("El archivo XML firmado no se guardó correctamente.");
            }

            // Crear el archivo ZIP y agregar el archivo XML firmado
            $zip = new \ZipArchive();
            $nombreArchivoZIP = session('empresa') . '-' . $guiaRemisionObj->grm_TD . '-' . $guiaRemisionObj->grm_Serie . '-' . $guiaRemisionObj->grm_Numero . '.zip';
            $rutaCompletaZIP = $rutaCarpetaXML . '/' . $nombreArchivoZIP;

            if ($zip->open($rutaCompletaZIP, \ZipArchive::CREATE) === TRUE) {
                // Agregar el archivo XML firmado al ZIP
                $zip->addFile($rutaCompletaXML, $nombreArchivoXML);
                $zip->close();

                // Verificar que el ZIP se creó correctamente
                if (!file_exists($rutaCompletaZIP)) {
                    throw new \Exception("No se pudo crear el archivo ZIP.");
                }
            } else {
                throw new \Exception("No se pudo abrir el archivo ZIP.");
            }
            return true;
        } catch (\Exception $e) {
            Log::error('Error al firmar o empaquetar el XML: ' . $e->getMessage());
            throw $e;
        }

    }

    function formatBase64String($base64String)
    {
        return chunk_split($base64String, 76, "\n");
    }

    function send($fecha, $ruc, $tipo, $serie, $numero, $identpedidoId)
    {
        try {
            $url = "https://gre-test.nubefact.com/v1/clientessol/test-85e5b0ae-255c-4891-a595-0b98c65c9854/oauth2/token";
            $params = [
                'grant_type' => 'password',
                'scope' => 'https://api-cpe.sunat.gob.pe',
                'client_id' => 'test-85e5b0ae-255c-4891-a595-0b98c65c9854',
                'client_secret' => 'test-Hty/M6QshYvPgItX2P0+Kw==',
                'username' => '10425068500MODDATOS',
                'password' => 'MODDATOS'
            ];
            try {
                $fechaConvertida = DateTime::createFromFormat('Y-m-d', $fecha)->format('Ymd');
            } catch (\Exception $e) {
                throw new \Exception("Formato de fecha inválido: " . $e->getMessage());
            }
            $response = $this->makeRequest('POST', $url, $params, [
                'Content-Type: application/x-www-form-urlencoded'
            ]);

            $this->token = $response->access_token;
            ///\Log::info('Token generado: ' . $this->token);
            $fecha = date('Ymd');
            $filePath = public_path("/storage/XML/{$fechaConvertida}/{$ruc}-{$tipo}-{$serie}-{$numero}.zip");
            $encodedFile = base64_encode(file_get_contents($filePath));
            $hashFile = hash_file('sha256', $filePath);
            $url1 = "https://gre-test.nubefact.com/v1/contribuyente/gem/comprobantes/{$ruc}-{$tipo}-{$serie}-{$numero}";
            $data = [
                'archivo' => [
                    'nomArchivo' => "{$ruc}-{$tipo}-{$serie}-{$numero}.zip",
                    'arcGreZip' => $encodedFile,
                    'hashZip' => $hashFile,
                ]
            ];

            $response1 = $this->makeRequest('POST', $url1, json_encode($data), [
                'Authorization: Bearer ' . $this->token,
                'Content-Type: application/json'
            ]);

            $this->ticket = $response1->numTicket;
            \Log::info('Ticket generado: ' . $this->ticket);
            //\Log::info('Respuesta recibida de SUNAT: ' . print_r($response1, true));
            $url2 = "https://gre-test.nubefact.com/v1/contribuyente/gem/comprobantes/envios/{$this->ticket}";
            $response2 = $this->makeRequest('GET', $url2, [], [
                'Authorization: Bearer ' . $this->token,
                'Content-Type: application/json'
            ]);

            //\Log::info('Respuesta: ' . json_encode($response2));
            $codRespuesta = isset($response2->codRespuesta) ? $response2->codRespuesta : null;
            if ($codRespuesta === '0') {
                $this->updateTicket($identpedidoId, $this->ticket, $codRespuesta, 'Aceptado');
            } else if ($codRespuesta !== null && isset($response2->error)) {
                $numError = isset($response2->error->numError) ? $response2->error->numError : 'N/A';
                $desError = isset($response2->error->desError) ? $response2->error->desError : 'Error desconocido';
                //\Log::error("Error en respuesta: Número de error: $numError, Descripción: $desError");
                $this->updateTicket($identpedidoId, $this->ticket, $codRespuesta, "Error $numError: $desError");
            }
            if (isset($response2->codRespuesta) && $response2->codRespuesta === '0' && isset($response2->arcCdr)) {
                $cdrDecodificado = base64_decode($response2->arcCdr);
                $nombreArchivoCDR = "R-{$ruc}-{$tipo}-{$serie}-{$numero}.zip";
                $directorio = public_path("/storage/XML/{$fechaConvertida}");
                if (!is_dir($directorio)) {
                    mkdir($directorio, 0755, true);
                }
                $rutaCompletaCDR = $directorio . '/' . $nombreArchivoCDR;
                file_put_contents($rutaCompletaCDR, $cdrDecodificado);
                $zip = new \ZipArchive;
                if ($zip->open($rutaCompletaCDR) === TRUE) {
                    // Extraer el contenido en el directorio correspondiente
                    $zip->extractTo($directorio);  // Corregido aquí
                    $zip->close();

                } else {
                    //echo "Error al extraer el archivo CDR.";
                }
                if (file_exists($rutaCompletaCDR)) {
                    // echo "Archivo CDR guardado correctamente en: " . $rutaCompletaCDR;
                } else {
                    throw new \Exception("Error al guardar el archivo CDR en la ruta especificada.");
                }
            }
        } catch (\Exception $e) {
            \Log::error('Error al enviar: ' . $e->getMessage());
            throw $e;
        }
    }

    private function makeRequest($method, $url, $data = [], $headers = [])
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);

        if ($method === 'POST') {
            curl_setopt($curl, CURLOPT_POSTFIELDS, is_array($data) ? http_build_query($data) : $data);
        }

        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        $response = curl_exec($curl);
        $error = curl_error($curl);
        curl_close($curl);

        if ($error) {
            throw new \Exception("Error en la solicitud: " . $error);
        }
        $jsonDecodedResponse = json_decode($response);
        if (json_last_error() === JSON_ERROR_NONE) {
            return $jsonDecodedResponse;
        } else {
            \Log::error('Respuesta no válida recibida: ' . $response);
            throw new \Exception('La respuesta recibida no es JSON válido.');
        }
    }

    public function updateTicket($id, $ticket, $codres, $mensaje)
    {
        try {
            DB::table('guiaremision')
                ->where('id', $id)
                ->update(['APISUNAT_NumTicket' => $ticket, 'grm_EstadoRespuesta' => $codres, 'grm_mensajeRespuesta' => $mensaje]);
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'No se pudo anular el documento'], 500);
        }
    }

    public function mostrarRegistroGuia()
    {
        return view(view: 'registroGuias');
    }

    public function listarregistroguiaremision(Request $request)
    {
        $fechaDesde = $request->input('fechadesde');
        $fechaHasta = $request->input('fechahasta');
        $medidas = Guiaremisioncab::select(['grm_Fecha', 'razonsocial', 'empresa', 'grm_Delivery_DeliveryCustomerParty_Name', 'grm_Delivery_DeliveryCustomerParty_ID', DB::raw("CONCAT(grm_Serie, '-', grm_Numero) as documento"), 'grm_EstadoRespuesta', 'grm_MensajeRespuesta'])
            ->whereBetween('grm_Fecha', [$fechaDesde, $fechaHasta])
            ->get();
        return response()->json($medidas);
    }

    public function generarReportePDFGuia($id)
    {
        $cabecera = DB::select("
        select a.*, e.RazonSocialEmpresa, e.direccion as dir, e.ubicacion,e.telefono, e.Ubigeo_emp, ifnull(ucli.Ubi_Departamento,'') as cli_Departamento, ifnull(ucli.Ubi_Provincia,'') as cli_Provincia,  ifnull(ucli.Ubi_Distrito,'') as cli_Distrito, ifnull(ini.Ubi_Departamento,'') as ini_Departamento,  ifnull(ini.Ubi_Provincia,'') as ini_Provincia, ifnull(ini.Ubi_Distrito,'') as ini_Distrito, ifnull(fin.Ubi_Departamento,'') as fin_Departamento, ifnull(fin.Ubi_Provincia,'') as fin_Provincia, ifnull(fin.Ubi_Distrito,'') as fin_Distrito 
        from GuiaRemision a 
        inner join Empresas e on a.Empresa=e.Empresa 
        left join UbigeoE ucli on a.grm_Delivery_DeliveryCustomerParty_Ubigeo = ucli.Ubi_Codigo 
        left join UbigeoE ini on a.grm_Shipment_OriginAddress_ID = ini.Ubi_Codigo 
        left join UbigeoE fin on a.grm_Shipment_DeliveryAddress_ID = fin.Ubi_Codigo 
        where a.id=?", [$id]);

        if (empty($cabecera)) {
            return abort(404, "Guia no encontrada.");
        }

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

        $empresa = session('empresa');
        $razonsocialempresa = $cabecera->RazonSocialEmpresa;
        $serie = $cabecera->grm_Serie;
        $numero = $cabecera->grm_Numero;
        $fecha = $cabecera->grm_Fecha;

        $qrCode = base64_encode(QrCode::format('png')->size(100)->generate($razonsocialempresa . '|' . $serie . '|' . $numero . '|' . $fecha));

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

        // Generar el PDF con la vista
        $pdf = Pdf::loadView('reportes.Reporteguiaremision', $data);

        // Definir el nombre del archivo y la ruta donde se guardará
        $nombreArchivoPDF = $empresa . '-' . $cabecera->grm_TD . '-' . $cabecera->grm_Serie . '-' . $cabecera->grm_Numero . '.pdf';
        $fechaActual = date('Ymd');
        $rutaCarpetaPDF = public_path('/storage/PDF/' . $fechaActual);

        // Crear la carpeta si no existe
        if (!is_dir($rutaCarpetaPDF)) {
            mkdir($rutaCarpetaPDF, 0777, true);
        }

        // Ruta completa del archivo
        $rutaCompletaPDF = $rutaCarpetaPDF . '/' . $nombreArchivoPDF;

        // Guardar el PDF en la ruta deseada
        $pdf->save($rutaCompletaPDF);

        // Comprobar si el archivo fue guardado correctamente
        if (!file_exists($rutaCompletaPDF)) {
            throw new \Exception("El archivo PDF no se guardó correctamente en la ruta: " . $rutaCompletaPDF);
        }
        
    }
}