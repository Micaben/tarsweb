<?php

namespace App\Http\Controllers;

use App\Models\Cabmovpro;
use App\Models\Comprobantescab;
use App\Models\Comprobantesdet;
use App\Models\Comprobantes_paymentterms;
use App\Models\Comprobantes_tributos;
use App\Models\Comprobantesd_tributos;
use App\Models\Comprobantes_Allowance;
use App\Models\ComprobantesL;
use App\Repositories\Repositorios;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Log;
use App\Services\SunatService;
use DOMDocument;
use DateTime;
use Greenter\XMLSecLibs\Sunat\SignedXml;
use Greenter\Model\Client\Client;
use Greenter\Model\Company\Company;
use Greenter\Model\Company\Address;
use Greenter\Model\Sale\FormaPagos\FormaPagoContado;
use Greenter\Model\Sale\Invoice;
use Greenter\Model\Sale\SaleDetail;
use Greenter\Model\Sale\Legend;
use App\Services\ParseadorJDOM2;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class VentasComprobantesController extends Controller
{
    protected $sunatService;


    public function __construct(SunatService $sunatService)
    {
        $this->sunatService = $sunatService;
    }

    public function mostrarComprobantesMantenimiento()
    {
        return view('Comprobantes');
    }

    public function guardarComprobantes(Request $request)
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
            $Iddoc = (int) $request->input('iddeldocumento'); // Aseguramos que el Iddoc sea un entero
            $isNew = !$Iddoc || empty($Iddoc);
            $TipoDocumento = $request->input('tipodocumento');
            $empresa = session('empresa');
            // Insertar o actualizar Cabmovpro y Movalmacencab
            $identpedidoId = $this->guardarComprobantescab($request, $isNew, $Iddoc);
            $this->guardarComprobantesdet($request, $identpedidoId, $isNew, $Iddoc);
            $this->guardarCuotasdet($request, $identpedidoId, $isNew, $Iddoc);
            $this->guardarComprobantesDespatch($request, $identpedidoId, $isNew, $Iddoc);
            $this->guardarComprobantes_tributos($request, $identpedidoId, $isNew, $Iddoc);
            $this->guardarComprobantes_Allowance($request, $identpedidoId, $isNew, $Iddoc);
            $this->guardarComprobantesd_tributos($request, $identpedidoId, $isNew, $Iddoc);
            $this->guardarComprobantesLeyendas($request, $identpedidoId, $isNew, $Iddoc);
            $xmlGeneradoCorrectamente = $this->CrearXMLComprobante($identpedidoId);
            if ($xmlGeneradoCorrectamente) {
                $this->sunatService->EnviarComprobanteElectronico($fecha, $empresa, $TipoDocumento, $serie, $numero);


            }
            //$this->generarReportePDFComprobante($identpedidoId);
            DB::commit();
            return response()->json(['success' => true, 'id' => $identpedidoId]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error durante la inserción: ' . $e->getMessage());
            Log::error('Request data: ' . json_encode($request->all()));
            return response()->json(['success' => false, 'errors' => ['message' => $e->getMessage()]]);
        }
    }

    public function guardarComprobantescab(Request $request, $isNew, $Iddoc)
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
                $identpedido->numero = $numero = ltrim($request->input('numeronota'), 0);
                $identpedido->td = $request->input('tipodocumento');
                $identpedido->save();
                $identpedidoId = $identpedido->id;

                $comcab = new Comprobantescab;
                $comcab->id = $identpedidoId;
            } else {
                // Actualizar registro existente
                $Identpedidos = Cabmovpro::findOrFail($Iddoc);
                $Identpedidos->empresa = session('empresa');
                $Identpedidos->fecha = $request->input('fechaproceso');
                $Identpedidos->td = $request->input('tipodocumento');
                $Identpedidos->save();
                $identpedidoId = $Identpedidos->id;

                $comcab = Comprobantescab::where('id', $identpedidoId)->firstOrFail();
            }
            //\Log::info($request->all());
            $comcab->TipoDocumento = $request->input('tipodocumento');
            $comcab->Serie = $request->input('cboserie_text');
            $comcab->Numero = $numero = ltrim($request->input('numeronota'), 0);
            $comcab->Ruc = session('empresa');
            $comcab->RazonSocialE = session('razonsocialempresa');
            $comcab->TipoDocIdR = $request->input('tipod');
            $comcab->NumDocIdr = $request->input('proveedor');
            $comcab->RazonSocialR = $request->input('cboproveedor_text');
            $comcab->DireccionReceptor = $request->input('direccion');
            $comcab->Baseimponible = $request->input('subtotal');
            $comcab->Inafecto = $request->input('inafecto') !== null && $request->input('inafecto') !== '' ? $request->input('inafecto') : 0.00;
            $comcab->ImporteTotal = $request->input('totalSuma');
            $comcab->ImporteSIGV = $request->input('subtotal');
            $comcab->igv = $request->input('igv');
            $comcab->Exonerado = '0.00';
            $comcab->TipoMoneda = $request->input('tipomoneda');
            $comcab->FechaEmision = $request->input('fechaproceso');
            $comcab->Fec_proceso = $request->input('fechaproceso');
            $comcab->Descuento = '0.00';
            $comcab->ISC = '0.00';
            $comcab->TipoDocRef = '00';
            $comcab->SerieDocRef = '00';
            $comcab->NumeroDocRef = '00';
            $comcab->MotivoRef = $request->input('cboconcepto_text');
            $comcab->ubigeo = $request->input('ubigeo');
            $comcab->igvporc = '18.00';
            $comcab->Cod_AfectaIGV = $request->input('codafec');
            $comcab->Cod_Motivo = $request->input('tipoperacion');
            $comcab->Gratuito = '0.00';
            $comcab->Gravada = $request->input('subtotal');
            $comcab->TipOperacion = $request->input('tipoperacion');
            $comcab->DescuentoG = '0.00';
            $comcab->codCliente = $request->input('proveedor');
            $comcab->Referencia = $request->input('comentarios');
            $comcab->condicionVenta = $request->input('cbocondicion_text');
            $comcab->NumOCompra = $request->input('ordencompra');
            $comcab->Cod_Bolsa = $request->input('proveedor');
            $comcab->UbigeoEmisor = '15032';
            $comcab->MtoIGVBase = $request->input('igv');
            $comcab->PorcIGVBase = '18.00';
            $comcab->OtrosCargos = '0.00';
            $comcab->MtoBruto = $request->input('subtotal');
            $comcab->por_DescuentoG = '0.00';
            $comcab->Det_Monto = '0.00';
            $comcab->IGV_Gratuito = '0.00';
            $comcab->Mto_Anticipos = '0.00';
            $comcab->Cod_Establecimiento = '0000';
            $comcab->Mto_ICBPER = '0.00';
            $comcab->Mto_GravadoICBPER = '0.00';
            $comcab->com_LineExtensionAmount = $request->input('subtotal');
            $comcab->com_TaxInclusiveAmount = $request->input('totalSuma');
            $comcab->com_AllowanceTotalAmount = '0.00';
            $comcab->com_ChargeTotalAmount = '0.00';
            $comcab->com_PrepaidAmount = '0.00';
            $comcab->fechav = $request->input('fechav');
            $comcab->com_PayableAmount = $request->input('totalSuma');
            $comcab->com_InvoiceTypeCode = $request->input('typecod');
            $comcab->com_NetoPagar = $request->input('netopagar');
            $comcab->com_Vendedor = $request->input('cbovendedor_text');
            $comcab->almacen = $request->input('cboalmacen');
            $comcab->concepto = $request->input('cboconcepto');
            $comcab->condicion = $request->input('cbocondicion');
            $comcab->hora = date('H:i:s');
            $comcab->usercre = session('nombre_usuario');
            $comcab->EstadoComprobante = 'P';
            $comcab->EnvioCorreo = '0';
            $comcab->EstadoEnvio = '0';
            $comcab->EstadoRespuesta = 'P';
            $comcab->MensajeRespuesta = 'Pendiente de Envio';
            $comcab->plazo = $request->input('plazo');
            $comcab->moneda = $request->input('cbomoneda');
            $comcab->vendedor = $request->input('cbovendedor');
            $comcab->com_IGV = $request->input('inigv', 0); // Por defecto a 0 si no está presente
            $comcab->com_Retencion = $request->input('conretencion', 0); // Por defecto a 0 si no está presente
            $comcab->com_Kardex = $request->input('iskardex', 0); // Por defecto a 0 si no está presente
            $comcab->com_Cortesia = $request->input('iscortesia', 0);
            $comcab->Mto_Retencion = $request->input('mtoretencion') !== null && $request->input('mtoretencion') !== '' ? $request->input('mtoretencion') : 0.00;
            $comcab->save();

            return $identpedidoId;
        } catch (\Exception $e) {
            Log::error('Error durante la inserción de cotizacion: ' . $e->getMessage());
            return response()->json(['error' => 'Error durante la inserción de cotizacion'], 500);
        }
    }

    public function guardarComprobantesdet(Request $request, $identpedidoId, $isNew, $Iddoc)
    {
        if (!$isNew) {
            Repositorios::eliminaDet('comprobantesd', $Iddoc);
        }

        $productos = json_decode($request->input('productos'), true);
        foreach ($productos as $producto) {
            $detalle = new Comprobantesdet;

            $detalle->id = $identpedidoId;
            $detalle->NumeroOrden = $producto['item'];
            $detalle->cod_producto = $producto['codigo'];
            $detalle->descripcion = $producto['descripcion'];
            $detalle->almacen = $producto['almacen'];
            $detalle->Unidad = $producto['unidad'];
            $detalle->cantidad = $producto['cantidad'];
            $detalle->ValorUnitario = $producto['precio'];
            $detalle->igv = $producto['igv'];
            $detalle->subtotal = $producto['total'];
            $detalle->total = $producto['total'];
            $detalle->cod_afectaigv = $producto['cod_afectaigv'];
            $detalle->precioref = $producto['precioref'];
            $detalle->descuento = '0.00';
            $detalle->ISC = '0.00';
            $detalle->por_Descuento = '0.00';
            $detalle->PorcentajeIGV = '18';
            $detalle->UnidadP = $producto['unidad'];
            $detalle->PriceTypeCode = $producto['estadoocheck'] == 1 ? '02' : '01';
            $detalle->cmd_TaxTotal_TaxAmount = $producto['igv'];
            $detalle->cmd_LineExtensionAmount = '0.00';
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

    public function guardarComprobantesDespatch(Request $request, $identpedidoId, $isNew, $Iddoc)
    {
        DB::statement("
        INSERT INTO ComprobantesDespatch (Id_Comprobantes, DocumentTypeCode, DocumentID)
        SELECT a.IdComprobante, '09', CONCAT(LPAD(b.grm_Serie, 4, '0'), '-', LPAD(b.grm_Numero, 8, '0'))
        FROM AlmacenCon  a
        INNER JOIN guiaremision b ON a.IdAlmacen  = b.Id
        WHERE a.IdComprobante = ? ", [$identpedidoId]);
    }

    public function guardarComprobantes_Allowance(Request $request, $identpedidoId, $isNew, $Iddoc)
    {
        $conRetencion = $request->input('conretencion', 0);
        $mtoretencion = $request->input('mtoretencion');

        if ($conRetencion) {
            $comcab = new Comprobantes_Allowance();
            $comcab->id_comprobantes = $identpedidoId;
            $comcab->com_AllowanceCharge_ChargeIndicator = 'false';
            $comcab->com_AllowanceCharge_AllowanceChargeReasonCode = '62';
            $comcab->com_AllowanceCharge_MultiplierFactorNumeric = '0.0300';
            $comcab->com_AllowanceCharge_Amount = $request->input('mtoretencion');
            $comcab->com_AllowanceCharge_BaseAmount = $request->input('totalSuma');
            $comcab->com_AllowanceCharge_currencyID = $request->input('tipomoneda');
        }

        try {
            $comcab->save();
        } catch (\Exception $e) {
            // Handle error (log it, return a response, etc.)
            Log::error('Error saving Comprobantes_Allowance: ' . $e->getMessage());
        }
    }

    public function guardarComprobantes_tributos(Request $request, $identpedidoId, $isNew, $Iddoc)
    {
        $codafec = $request->input('codafec');
        $totalSuma = $request->input('totalSuma');
        $cortesia = $request->input('iscortesia', 0);
        $comcab = new Comprobantes_tributos;
        $comcab->id_comprobantes = $identpedidoId;
        $comcab->com_TaxSubtotal_TaxableAmount = $request->input('totalSuma');
        $comcab->com_TaxSubtotal_TaxAmount = $request->input('igv');

        if (($totalSuma > 0 || in_array($codafec, ['11', '13'])) && $cortesia == 0 && $codafec != '40') {
            $comcab->com_TaxSubtotal_TaxCategory = 'S';
            $comcab->com_TaxSubtotal_TaxCategory_TaxScheme_ID = '1000';
            $comcab->com_TaxSubtotal_TaxCategory_TaxScheme_Name = 'IGV';
            $comcab->com_TaxSubtotal_TaxCategory_TaxScheme_TaxTypeCode = 'VAT';
        } elseif ($totalSuma > 0 && $cortesia == 0 && $codafec != '40') {
            $comcab->com_TaxSubtotal_TaxCategory = '0';
            $comcab->com_TaxSubtotal_TaxCategory_TaxScheme_ID = '9998';
            $comcab->com_TaxSubtotal_TaxCategory_TaxScheme_Name = 'INA';
            $comcab->com_TaxSubtotal_TaxCategory_TaxScheme_TaxTypeCode = 'FRE';
        } elseif ($totalSuma > 0) {
            $comcab->com_TaxSubtotal_TaxCategory = 'Z';
            $comcab->com_TaxSubtotal_TaxCategory_TaxScheme_ID = '9996';
            $comcab->com_TaxSubtotal_TaxCategory_TaxScheme_Name = 'GRA';
            $comcab->com_TaxSubtotal_TaxCategory_TaxScheme_TaxTypeCode = 'FRE';
        } elseif ($codafec == '40') {
            $comcab->com_TaxSubtotal_TaxCategory = 'G';
            $comcab->com_TaxSubtotal_TaxCategory_TaxScheme_ID = '9995';
            $comcab->com_TaxSubtotal_TaxCategory_TaxScheme_Name = 'EXP';
            $comcab->com_TaxSubtotal_TaxCategory_TaxScheme_TaxTypeCode = 'FRE';
        }
        try {
            $comcab->save();
        } catch (\Exception $e) {
            // Handle error (log it, return a response, etc.)
            Log::error('Error saving Comprobantes_tributos: ' . $e->getMessage());
        }
    }

    public function guardarComprobantesd_tributos(Request $request, $identpedidoId, $isNew, $Iddoc)
    {
        $codafec = $request->input('codafec');
        $totalSuma = $request->input('totalSuma');
        $cortesia = $request->input('iscortesia', 0);
        $productos = json_decode($request->input('productos'), true);

        if ($cortesia == 0 && $codafec != '40') {
            foreach ($productos as $producto) {
                $detalle = new Comprobantesd_tributos;
                $detalle->id_comprobantes = $identpedidoId;
                $detalle->cmd_NumeroOrden = $producto['item'];
                $detalle->cmd_TaxSubtotal_TaxableAmount = $producto['total'];
                $detalle->cmd_TaxSubtotal_TaxAmount = $producto['igvproducto'];
                $detalle->cmd_TaxSubtotal_TaxCategory_ID = 'S';
                $detalle->cmd_TaxSubtotal_TaxCategory_Percent = '18.00';
                $detalle->cmd_TaxSubtotal_TaxCategory_TaxExemptionReasonCode = $codafec;
                $detalle->cmd_TaxSubtotal_TaxCategory_TierRange = '';
                $detalle->cmd_TaxSubtotal_TaxCategory_TaxScheme_ID = '1000';
                $detalle->cmd_TaxSubtotal_TaxCategory_TaxScheme_Name = 'IGV';
                $detalle->cmd_TaxSubtotal_TaxCategory_TaxScheme_TaxTypeCode = 'VAT';
                $detalle->save();
            }
        } else if ($cortesia == 1) {
            foreach ($productos as $producto) {
                $detalle = new Comprobantesd_tributos;
                $detalle->id_comprobantes = $identpedidoId;
                $detalle->cmd_NumeroOrden = $producto['item'];
                $detalle->cmd_TaxSubtotal_TaxAmount = $producto['igvproducto'];
                $detalle->cmd_TaxSubtotal_TaxCategory_ID = 'Z';
                $detalle->cmd_TaxSubtotal_TaxCategory_Percent = '18.00';
                $detalle->cmd_TaxSubtotal_TaxCategory_TaxExemptionReasonCode = '13';
                $detalle->cmd_TaxSubtotal_TaxCategory_TierRange = '';
                $detalle->cmd_TaxSubtotal_TaxCategory_TaxScheme_ID = '9996';
                $detalle->cmd_TaxSubtotal_TaxCategory_TaxScheme_Name = 'GRA';
                $detalle->cmd_TaxSubtotal_TaxCategory_TaxScheme_TaxTypeCode = 'FRE';
                $detalle->save();
            }
        } else if ($cortesia == 0 && $codafec == '40') {
            foreach ($productos as $producto) {
                $detalle = new Comprobantesd_tributos;
                $detalle->id_comprobantes = $identpedidoId;
                $detalle->cmd_NumeroOrden = $producto['item'];
                $detalle->cmd_TaxSubtotal_TaxableAmount = $producto['total'];
                $detalle->cmd_TaxSubtotal_TaxAmount = $producto['igvproducto'];
                $detalle->cmd_TaxSubtotal_TaxCategory_ID = 'G';
                $detalle->cmd_TaxSubtotal_TaxCategory_Percent = '18.00';
                $detalle->cmd_TaxSubtotal_TaxCategory_TaxExemptionReasonCode = $codafec;
                $detalle->cmd_TaxSubtotal_TaxCategory_TierRange = '';
                $detalle->cmd_TaxSubtotal_TaxCategory_TaxScheme_ID = '9995';
                $detalle->cmd_TaxSubtotal_TaxCategory_TaxScheme_Name = 'EXP';
                $detalle->cmd_TaxSubtotal_TaxCategory_TaxScheme_TaxTypeCode = 'FRE';
                $detalle->save();
            }
        }
    }

    public function guardarComprobantesLeyendas(Request $request, $identpedidoId, $isNew, $Iddoc)
    {
        $codafec = $request->input('codafec');
        $cortesia = $request->input('iscortesia', 0);
        $comcab = new ComprobantesL;
        $comcab->Id_Comprobantes = $identpedidoId;
        $total = $request->input('totalSuma');
        $moneda = $request->input('tipomoneda') == 'PEN' ? 'SOLES' : 'DOLARES';
        if ($cortesia == 0 && $codafec != '40') {
            $comcab->ley_Codigo = '1000';
            $result = DB::select("SELECT numero_a_letras(?, ?) AS ley_texto", [$total, $moneda]);
            $comcab->ley_Texto = $result[0]->ley_texto;
        } else if ($cortesia == 1) {
            $comcab->ley_Codigo = '1002';
            $comcab->ley_Texto = 'TRANSFERENCIA GRATUITA DE UN BIEN Y/O SERVICIO PRESTADO GRATUITAMENTE';
        }
        $comcab->save();
    }

    public function guardarCuotasdet(Request $request, $identpedidoId, $isNew, $Iddoc)
    {
        // Evaluar si el registro es nuevo o si se deben eliminar los detalles anteriores
        if (!$isNew) {
            Repositorios::eliminaDet('comprobantes_paymentterms', $Iddoc);
        }

        // Obtener el valor del campo 'plazo'
        $plazo = $request->input('plazo');
        $cuotas = json_decode($request->input('cuotas'), true);
        //\Log::info('Cuotas: ', $cuotas);
        $tipomoneda = $request->input('tipomoneda');
        // Si el plazo es menor a 0, solo registrar una fila
        if ($plazo == 0) {
            $detalle = new Comprobantes_paymentterms;
            $detalle->id_comprobantes = $identpedidoId;
            $detalle->com_PaymentTerms_ID = 'FormaPago';
            $detalle->com_PaymentTerms_PaymentMeansID = 'Contado'; // Si es plazo negativo, pago contado
            $detalle->com_PaymentTerms_PaymentPercent = '0.00';
            $detalle->com_PaymentTerms_Amount = $request->input('netopagar');
            $detalle->com_PaymentTerms_PaymentDueDate = $request->input('fechaproceso');
            $detalle->com_PaymentTerms_currencyID = $tipomoneda;
            $detalle->com_PaymentTerms_Amount_PEN = '0.00';

            // Guardar el registro
            $detalle->save();

        } else {
            Log::info('Cuotas:', ['cuotas' => $cuotas]);

            if (empty($cuotas) || count($cuotas) === 0) {
                Log::info('No hay cuotas, insertando registros vacíos');

                for ($i = 0; $i < 2; $i++) {
                    $detalle = new Comprobantes_paymentterms;

                    $detalle->id_comprobantes = $identpedidoId;
                    $detalle->com_PaymentTerms_ID = 'FormaPago';

                    if ($i === 0) {
                        // Primer registro
                        $detalle->com_PaymentTerms_PaymentMeansID = 'Credito';
                    } else {
                        // Segundo registro con cuota formateada
                        $detalle->com_PaymentTerms_PaymentMeansID = 'Cuota' . str_pad('001', 3, '0', STR_PAD_LEFT);
                    }

                    $detalle->com_PaymentTerms_PaymentPercent = '0.00';
                    $detalle->com_PaymentTerms_Amount = $request->input('netopagar');
                    $detalle->com_PaymentTerms_PaymentDueDate = $request->input('fechaproceso');
                    $detalle->com_PaymentTerms_currencyID = $tipomoneda;
                    $detalle->com_PaymentTerms_Amount_PEN = '0.00';
                    Log::info('Guardando registro de paymentterms', ['detalle' => $detalle]);

                    $detalle->save(); // Guardar cada registro
                }
            } else {
                Log::info('Cuotas encontradas, insertando registros de cuotas', ['cuotas' => $cuotas]);

                $detalle = new Comprobantes_paymentterms;

                $detalle->id_comprobantes = $identpedidoId;
                $detalle->com_PaymentTerms_ID = 'FormaPago';
                $detalle->com_PaymentTerms_PaymentMeansID = 'Credito';
                $detalle->com_PaymentTerms_PaymentPercent = '0.00';
                $detalle->com_PaymentTerms_Amount = $request->input('netopagar');
                $detalle->com_PaymentTerms_PaymentDueDate = $cuotas[0]['fecha'];
                $detalle->com_PaymentTerms_currencyID = $tipomoneda;
                $detalle->com_PaymentTerms_Amount_PEN = '0.00';

                // Guardar el registro de la primera cuota
                $detalle->save();

                // Ahora guardar las demás cuotas
                foreach ($cuotas as $cuota) {
                    $detalle = new Comprobantes_paymentterms;
                    $detalle->id_comprobantes = $identpedidoId;
                    $detalle->com_PaymentTerms_ID = 'FormaPago';
                    $numeroCuotaFormateada = 'Cuota' . str_pad('001', 3, '0', STR_PAD_LEFT);
                    $detalle->com_PaymentTerms_PaymentMeansID = $numeroCuotaFormateada;
                    $detalle->com_PaymentTerms_PaymentPercent = '0.00';
                    $detalle->com_PaymentTerms_Amount = $cuota['monto'];
                    $detalle->com_PaymentTerms_PaymentDueDate = $cuota['fecha'];
                    $detalle->com_PaymentTerms_currencyID = $tipomoneda;
                    $detalle->com_PaymentTerms_Amount_PEN = '0.00';

                    // Guardar el registro
                    $detalle->save();
                }
            }
        }
    }

    public function mostrarRegistrosComprobantes(Request $request)
    {
        $mes = $request->query('mes');
        $anio = $request->query('anio');

        // Validar los parámetros
        $request->validate([
            'mes' => 'required|integer|between:1,12',
            'anio' => 'required|integer|min:2000|max:' . date('Y'),
        ]);

        $sql = "SELECT CONCAT(a.serie, '-', a.numero) AS Documento,
                   DATE_FORMAT(a.FechaEmision, '%d/%m/%Y') AS Fecha,
                   a.NumDocIdR,a.NumOCompra as oc,
                   b.RazonSocial,
                   a.usercre, a.EstadoComprobante, T.factor2, a.ImporteTotal,
                   a.id
            FROM comprobantesefact a
            LEFT JOIN cliente b ON a.NumDocIdR = b.ruc
            left join tablavarios t on t.cod=a.moneda
            WHERE a.EstadoComprobante IN ('A', 'G', 'P')
              AND MONTH(FechaEmision) = ?
              AND YEAR(FechaEmision) = ?
               and t.clase='MON'
            ORDER BY Fecha ASC";

        $resultados = DB::select($sql, [$mes, $anio]);
        $notaIngresos = array_map(function ($row) {
            return [
                'documento' => $row->Documento,
                'fecha' => $row->Fecha,
                'ruc' => $row->NumDocIdR,
                'cliente' => $row->RazonSocial,
                'o/compra' => $row->oc,
                'estado' => $row->EstadoComprobante,
                'mda' => $row->factor2,
                'total' => $row->ImporteTotal,
                'id' => $row->id,
            ];
        }, $resultados);
        return response()->json($notaIngresos);
    }

    public function buscarregistroComprobantes(Request $request, $id)
    {
        try {
            $query = "select a.TipoDocumento,a.serie,a.numero,b.ruc,b.RazonSocial,a.FechaEmision,
            a.Concepto, a.Moneda,a.NumOCompra,a.Referencia, a.EstadoComprobante,a.usercre,
            a.BaseImponible,a.BaseImponible,a.IGV,tv.Factor2,a.ImporteTotal,cv.id as condicion, a.vendedor,
            tv.Deascripcion,a.id,e.Descripcion,c.Descripcion,a.com_IGV, a.plazo, a.FechaV, a.com_Retencion,
            b.direccion,b.ubigeo, a.Cod_AfectaIGV, a.TipoDocIdR, a.tipomoneda,a.ImporteTotal, a.com_NetoPagar ,a.inafecto,a.TipOperacion, a.Mto_Retencion, a.com_InvoiceTypeCode
            from comprobantesefact a inner join Cliente b on b.ruc=a.NumDocIdR
             left join ConceptoVenta c on c.id=a.concepto
             left join Condicionventa cv on cv.id=a.condicion
             left join vendedores v on v.id=a.vendedor 
             left join TablaVarios tv on a.Moneda=tv.cod and tv.clase ='MON' 
             left join Estados e ON e.Estado = a.EstadoComprobante where a.id=?";

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

    public function buscardetalleComprobantes($id)
    {
        try {
            // Consulta para obtener los datos
            $detalleGuia = Comprobantesdet::selectRaw('NumeroOrden as item, n.cod_Producto as producto, p.Descripcion, color.Descripcion AS color, u.Umedida, p.Ancho, n.Cantidad, n.ValorUnitario as Precio, n.Subtotal as Totalpro, CASE WHEN n.detalle IS NULL THEN "" ELSE n.detalle END AS detalle')
                ->from('comprobantesd as n')
                ->join('productos as p', 'p.codproducto', '=', 'n.cod_Producto')
                ->leftJoin('Color as color', 'color.Id', '=', 'p.IdColor')
                ->leftJoin('UnidadMed as u', 'u.Umedida', '=', 'p.IdUMedida')
                ->where('n.id', $id)
                ->orderBy('n.NumeroOrden')
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
                ->update(['estado' => 'A', 'valortotal' => '0.00', 'igv' => '0.00', 'total' => '0.00', 'baseimp' => '0.00']);

            // Elimina los registros asociados en la tabla Pedidosdet
            DB::table('notapedidodetalle')
                ->where('id', $id)
                ->delete();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'No se pudo anular el documento'], 500);
        }
    }

    function CrearXMLComprobante1($idGuia): bool
    {
        $guiaRemisionObj = DB::table('comprobantesefact as ce')
            ->join('comprobantesd_tributos as ct', 'ct.Id_Comprobantes', '=', 'ce.Id')
            ->join('empresas as e', 'e.empresa', '=', 'ce.ruc')
            ->where('ce.Id', $idGuia)
            ->select(
                'ce.*',
                'ct.cmd_TaxSubtotal_TaxCategory_ID',
                'ct.cmd_TaxSubtotal_TaxCategory_Percent',
                'ct.cmd_TaxSubtotal_TaxCategory_TaxScheme_ID',
                'ct.cmd_TaxSubtotal_TaxCategory_TaxScheme_Name',
                'ct.cmd_TaxSubtotal_TaxCategory_TaxScheme_TaxTypeCode',
                'e.Direccion',
                'e.Ubigeo_emp',
                'e.Ubi_Departamento',
                'e.Ubi_Provincia',
                'e.Ubi_Distrito',
            )
            ->first();

        $despatch = DB::table('comprobantesdespatch as cd')
            // ->join('comprobantesdespatch as cd', 'cd.Id_Comprobantes', '=', 'ce.Id')
            ->where('cd.Id_Comprobantes', $idGuia)
            // ->select('ce.*', 'cd.DocumentID', 'cd.DocumentTypeCode')
            ->first();


        $detalles = DB::table('comprobantesd as cd')
            ->join('productos as p', 'cd.cod_Producto', '=', 'p.CodProducto')
            //->join('comprobantesd_tributos as ct', 'ct.Id_Comprobantes', '=', 'cd.Id')
            ->where('cd.id', $idGuia)
            // ->select(
            //    'cd.*',
            //    'ct.cmd_TaxSubtotal_TaxCategory_ID',
            //    'ct.cmd_TaxSubtotal_TaxCategory_TaxScheme_ID',
            //     'ct.cmd_TaxSubtotal_TaxCategory_TaxScheme_Name',
            //    'ct.cmd_TaxSubtotal_TaxCategory_TaxScheme_TaxTypeCode'
            // )
            ->get();
        $numeroItems = $detalles->count();


        $cuotas = DB::table('Comprobantes_paymentterms')
            ->where('Id_Comprobantes', $idGuia)
            ->get();

        $allowance = DB::table('comprobantes_allowancecharge as ca')
            ->where('ca.Id_Comprobantes', $idGuia)
            ->first();

        $leyendas = DB::table('comprobantesl')
            ->where('Id_Comprobantes', $idGuia)
            ->first();

        $doc = new DOMDocument();
        $doc->formatOutput = FALSE;
        $doc->preserveWhiteSpace = TRUE;
        $doc->encoding = 'utf-8';

        $xml = '<?xml version="1.0" encoding="UTF-8"?>
        <Invoice xmlns="urn:oasis:names:specification:ubl:schema:xsd:Invoice-2" xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2" 
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
            <cbc:ProfileID schemeAgencyName="PE:SUNAT" schemeName="SUNAT:Identificador de Tipo de Operacion" schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo17">' . $guiaRemisionObj->TipoDocumento . '</cbc:ProfileID>
	            <cbc:ID>' . $guiaRemisionObj->Serie . '-' . $guiaRemisionObj->Numero . '</cbc:ID>
	                <cbc:IssueDate>' . $guiaRemisionObj->FechaEmision . '</cbc:IssueDate>
	                <cbc:IssueTime>' . $guiaRemisionObj->hora . '</cbc:IssueTime>
                        <cbc:InvoiceTypeCode listAgencyName="PE:SUNAT" listID="0101" listName="Tipo de Documento" listSchemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo51" listURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo01">' . $guiaRemisionObj->TipoDocumento . '</cbc:InvoiceTypeCode>
                              <cbc:Note languageLocaleID="' . $leyendas->ley_Codigo . '">' . $leyendas->ley_Texto . '</cbc:Note>
                                <cbc:DocumentCurrencyCode listAgencyName="United Nations Economic Commission for Europe" listID="ISO 4217 Alpha" listName="Currency">' . $guiaRemisionObj->TipoMoneda . '</cbc:DocumentCurrencyCode> 
                                    <cbc:LineCountNumeric>' . $numeroItems . '</cbc:LineCountNumeric>
                                    <cac:OrderReference>
                                        <cbc:ID>' . $guiaRemisionObj->NumOCompra . '</cbc:ID>
                                    </cac:OrderReference>';
        if ($despatch):
            $xml .= '      <cac:DespatchDocumentReference>
                                        <cbc:ID>' . $despatch->DocumentID . '</cbc:ID>
                                        <cbc:DocumentTypeCode listAgencyName="PE:SUNAT" listName="Tipo de Documento" listURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo01">' . $despatch->DocumentTypeCode . '</cbc:DocumentTypeCode>
                                    </cac:DespatchDocumentReference>';
        endif;

        $xml .= '<cac:Signature>
                                        <cbc:ID>S' . $guiaRemisionObj->Serie . '-' . $guiaRemisionObj->Numero . '</cbc:ID>
                                            <cac:SignatoryParty>
                                                <cac:PartyIdentification>
                                                    <cbc:ID>' . $guiaRemisionObj->Ruc . '</cbc:ID>
                                                </cac:PartyIdentification>
                                                    <cac:PartyName>
                                                        <cbc:Name><![CDATA[' . $guiaRemisionObj->RazonSocialE . ']]></cbc:Name>
                                                    </cac:PartyName>
                                            </cac:SignatoryParty>
                                                <cac:DigitalSignatureAttachment>
                                                    <cac:ExternalReference>
                                                        <cbc:URI>S' . $guiaRemisionObj->Serie . '-' . $guiaRemisionObj->Numero . '</cbc:URI>
                                                    </cac:ExternalReference>
                                                </cac:DigitalSignatureAttachment>
                                    </cac:Signature>';

        $xml .= '<cac:AccountingSupplierParty>
                    <cac:Party>
                        <cac:PartyIdentification>
                            <cbc:ID schemeAgencyName="PE:SUNAT" schemeID="6" schemeName="Documento de Identidad" schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo06">' . $guiaRemisionObj->Ruc . '</cbc:ID>
                        </cac:PartyIdentification>
                                    <cac:PartyName>
                                        <cbc:Name>
                                            <![CDATA[ ' . $guiaRemisionObj->RazonSocialE . ' ]]>
                                        </cbc:Name>
                                    </cac:PartyName>
                                <cac:PartyLegalEntity>
                                    <cbc:RegistrationName>
                                        <![CDATA[ ' . $guiaRemisionObj->RazonSocialE . ' ]]>
                                    </cbc:RegistrationName>
                                    <cac:RegistrationAddress>
                                        <cbc:AddressTypeCode listAgencyName="PE:SUNAT" listName="Establecimientos anexos">0000</cbc:AddressTypeCode>
                                        <cbc:CityName>
                                            <![CDATA[ ' . $guiaRemisionObj->Ubi_Departamento . ' ]]>
                                        </cbc:CityName>
                                        <cbc:CountrySubentity>
                                            <![CDATA[ ' . $guiaRemisionObj->Ubi_Departamento . ' ]]>
                                        </cbc:CountrySubentity>
                                        <cbc:CountrySubentityCode>
                                            <![CDATA[ ' . $guiaRemisionObj->Ubigeo_emp . ' ]]>
                                        </cbc:CountrySubentityCode>
                                        <cbc:District>
                                            <![CDATA[ ' . $guiaRemisionObj->Ubi_Distrito . ' ]]>
                                        </cbc:District>
                                            <cac:AddressLine>
                                                <cbc:Line>
                                                    <![CDATA[ ' . $guiaRemisionObj->Direccion . ' ]]>
                                                </cbc:Line>
                                            </cac:AddressLine>
                                            <cac:Country>
                                                <cbc:IdentificationCode>PE</cbc:IdentificationCode>
                                            </cac:Country>
                                    </cac:RegistrationAddress>
                                </cac:PartyLegalEntity>
                    </cac:Party>
                </cac:AccountingSupplierParty>';

        $xml .= '<cac:AccountingCustomerParty>
                    <cac:Party>
                        <cac:PartyIdentification>
                            <cbc:ID schemeAgencyName="PE:SUNAT" schemeID="' . $guiaRemisionObj->TipoDocIdR . '" schemeName="Documento de Identidad" schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo06">' . $guiaRemisionObj->NumDocIdR . '</cbc:ID>
                        </cac:PartyIdentification>
                            <cac:PartyName>
                                <cbc:Name>' . $guiaRemisionObj->RazonSocialR . '</cbc:Name>
                            </cac:PartyName>
                                <cac:PartyLegalEntity>
                                    <cbc:RegistrationName>' . $guiaRemisionObj->RazonSocialR . '</cbc:RegistrationName>
                                </cac:PartyLegalEntity>
                    </cac:Party>
                </cac:AccountingCustomerParty>';

        $xml .= '<cac:DeliveryTerms>
                    <cac:DeliveryLocation>
                        <cac:Address>
                            <cbc:StreetName>
                                <![CDATA[ ' . $guiaRemisionObj->DireccionReceptor . ' ]]>
                            </cbc:StreetName>
                            <cbc:CitySubdivisionName/>
                            <cac:Country>
                                <cbc:IdentificationCode listAgencyName="United Nations Economic Commission for Europe" listID="ISO 3166-1" listName="Country">PE</cbc:IdentificationCode>
                            </cac:Country>
                        </cac:Address>
                    </cac:DeliveryLocation>
                </cac:DeliveryTerms>';

        foreach ($cuotas as $v) {
            $xml .= '<cac:PaymentTerms>
                    <cbc:ID>' . $v->com_PaymentTerms_ID . '</cbc:ID>
                    <cbc:PaymentMeansID>' . $v->com_PaymentTerms_PaymentMeansID . '</cbc:PaymentMeansID>
                    <cbc:Amount currencyID="' . $v->com_PaymentTerms_currencyID . '">' . $v->com_PaymentTerms_Amount . '</cbc:Amount>
                    <cbc:PaymentDueDate>' . $v->com_PaymentTerms_PaymentDueDate . '</cbc:PaymentDueDate>
                    </cac:PaymentTerms>';
        }

        if ($allowance):
            $xml .= ' <cac:AllowanceCharge>
                        <cbc:ChargeIndicator>' . $allowance->com_AllowanceCharge_ChargeIndicator . '</cbc:ChargeIndicator>
                        <cbc:AllowanceChargeReasonCode>' . $allowance->com_AllowanceCharge_AllowanceChargeReasonCode . '</cbc:AllowanceChargeReasonCode>
                        <cbc:MultiplierFactorNumeric>' . $allowance->com_AllowanceCharge_MultiplierFactorNumeric . '</cbc:MultiplierFactorNumeric>
                        <cbc:Amount currencyID="' . $guiaRemisionObj->TipoMoneda . '">' . $allowance->com_AllowanceCharge_Amount . '</cbc:Amount>
                        <cbc:BaseAmount currencyID="' . $guiaRemisionObj->TipoMoneda . '">' . $allowance->com_AllowanceCharge_BaseAmount . '</cbc:BaseAmount>
                    </cac:AllowanceCharge>';
        endif;

        $xml .= '<cac:TaxTotal>
                        <cbc:TaxAmount currencyID="' . $guiaRemisionObj->TipoMoneda . '">' . $guiaRemisionObj->IGV . '</cbc:TaxAmount>
                            <cac:TaxSubtotal>
                                <cbc:TaxableAmount currencyID="' . $guiaRemisionObj->TipoMoneda . '">' . $guiaRemisionObj->ImporteSIGV . '</cbc:TaxableAmount>
                                <cbc:TaxAmount currencyID="' . $guiaRemisionObj->TipoMoneda . '">' . $guiaRemisionObj->IGV . '</cbc:TaxAmount>
                                <cac:TaxCategory>                                    
                                    <cbc:ID schemeAgencyName="United Nations Economic Commission for Europe" schemeID="UN/ECE 5305" schemeName="Tax Category Identifier">' . $guiaRemisionObj->cmd_TaxSubtotal_TaxCategory_ID . '</cbc:ID>
                                    <cac:TaxScheme>
                                        <cbc:ID schemeAgencyID="6" schemeID="UN/ECE 5153">' . $guiaRemisionObj->cmd_TaxSubtotal_TaxCategory_TaxScheme_ID . '</cbc:ID>
                                        <cbc:Name>' . $guiaRemisionObj->cmd_TaxSubtotal_TaxCategory_TaxScheme_Name . '</cbc:Name>
                                        <cbc:TaxTypeCode>' . $guiaRemisionObj->cmd_TaxSubtotal_TaxCategory_TaxScheme_TaxTypeCode . '</cbc:TaxTypeCode>
                                    </cac:TaxScheme>
                                </cac:TaxCategory>
                            </cac:TaxSubtotal>
                </cac:TaxTotal>';

        $xml .= '<cac:LegalMonetaryTotal>
                    <cbc:LineExtensionAmount currencyID="' . $guiaRemisionObj->TipoMoneda . '">' . $guiaRemisionObj->ImporteSIGV . '</cbc:LineExtensionAmount>
                    <cbc:TaxInclusiveAmount currencyID="' . $guiaRemisionObj->TipoMoneda . '">' . $guiaRemisionObj->ImporteTotal . '</cbc:TaxInclusiveAmount>
                    <cbc:AllowanceTotalAmount currencyID="' . $guiaRemisionObj->TipoMoneda . '">0.0</cbc:AllowanceTotalAmount>
                    <cbc:PayableAmount currencyID="' . $guiaRemisionObj->TipoMoneda . '">' . $guiaRemisionObj->ImporteTotal . '</cbc:PayableAmount>
                </cac:LegalMonetaryTotal>';


        foreach ($detalles as $v) {
            $xml .= '<cac:InvoiceLine>
                                <cbc:ID>' . $v->NumeroOrden . '</cbc:ID>
                                <cbc:InvoicedQuantity unitCode="' . $v->Unidad . '" unitCodeListAgencyName="United Nations Economic Commission for Europe" unitCodeListID="UN/ECE rec 20">' . $v->Cantidad . '</cbc:InvoicedQuantity>
                                <cbc:LineExtensionAmount currencyID="' . $guiaRemisionObj->TipoMoneda . '">' . $v->total . '</cbc:LineExtensionAmount>
                                <cac:PricingReference>
                                    <cac:AlternativeConditionPrice>
                                        <cbc:PriceAmount currencyID="' . $guiaRemisionObj->TipoMoneda . '">' . $v->precioref . '</cbc:PriceAmount>
                                        <cbc:PriceTypeCode listAgencyName="PE:SUNAT" listName="Tipo de Precio" listURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo16">' . $v->PriceTypeCode . '</cbc:PriceTypeCode>
                                    </cac:AlternativeConditionPrice>
                                </cac:PricingReference>
                            <cac:TaxTotal>
                                <cbc:TaxAmount currencyID="' . $guiaRemisionObj->TipoMoneda . '">' . $v->IGV . '</cbc:TaxAmount>
                                    <cac:TaxSubtotal>
                                        <cbc:TaxableAmount currencyID="' . $guiaRemisionObj->TipoMoneda . '">' . $v->total . '</cbc:TaxableAmount>
                                        <cbc:TaxAmount currencyID="' . $guiaRemisionObj->TipoMoneda . '">' . $v->IGV . '</cbc:TaxAmount>
                                        <cac:TaxCategory>
                                            <cbc:ID schemeAgencyName="United Nations Economic Commission for Europe" schemeID="UN/ECE 5305" schemeName="Tax Category Identifier">' . $guiaRemisionObj->cmd_TaxSubtotal_TaxCategory_ID . '</cbc:ID>
                                            <cbc:Percent>' . $guiaRemisionObj->cmd_TaxSubtotal_TaxCategory_Percent . '</cbc:Percent>
                                            <cbc:TaxExemptionReasonCode listAgencyName="PE:SUNAT" listName="Afectacion del IGV" listURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo07">' . $v->cod_afectaigv . '</cbc:TaxExemptionReasonCode>
                                            <cac:TaxScheme>
                                                <cbc:ID schemeAgencyName="PE:SUNAT" schemeName="Codigo de tributos" schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo05">' . $guiaRemisionObj->cmd_TaxSubtotal_TaxCategory_TaxScheme_ID . '</cbc:ID>
                                                <cbc:Name>' . $guiaRemisionObj->cmd_TaxSubtotal_TaxCategory_TaxScheme_Name . '</cbc:Name>
                                                <cbc:TaxTypeCode>' . $guiaRemisionObj->cmd_TaxSubtotal_TaxCategory_TaxScheme_TaxTypeCode . '</cbc:TaxTypeCode>
                                            </cac:TaxScheme>
                                        </cac:TaxCategory>
                                    </cac:TaxSubtotal>
                            </cac:TaxTotal>
                            <cac:Item>
                                <cbc:Description>
                                <![CDATA[' . $v->Descripcion . ' ]]>
                                </cbc:Description>
                            </cac:Item>
                            <cac:Price>
                                <cbc:PriceAmount currencyID="' . $guiaRemisionObj->TipoMoneda . '">' . $v->ValorUnitario . '</cbc:PriceAmount>
                            </cac:Price>
                        </cac:InvoiceLine>';
        }

        $xml .= '</Invoice>';
        $certificado = storage_path('certificates/certificate.pem');
        //$certPassword = 'userfact2022';
        //file_put_contents(storage_path('app/xml/antes_firma.xml'), $xml);
        //$xmlFirmado = $this->firmarXML($xml, $certificado, $certPassword);
        $nombreArchivoXML = session('empresa') . '-' . $guiaRemisionObj->TipoDocumento . '-' . $guiaRemisionObj->Serie . '-' . $guiaRemisionObj->Numero . '.xml';
        $fechaActual = date('Ymd');
        $rutaCarpetaXML = public_path('/storage/Facturas/XML/' . $fechaActual);

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
            $nombreArchivoZIP = session('empresa') . '-' . $guiaRemisionObj->TipoDocumento . '-' . $guiaRemisionObj->Serie . '-' . $guiaRemisionObj->Numero . '.zip';
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

    public function mostrarNotacreditoMantenimiento()
    {
        return view('Notacredito');
    }

    public function mostrarNotadebitoMantenimiento()
    {
        return view('Notadebito');
    }

    function CrearXMLComprobante($idGuia): bool
    {
        $guiaRemisionObj = DB::table('comprobantesefact as ce')
            ->join('comprobantesd_tributos as ct', 'ct.Id_Comprobantes', '=', 'ce.Id')
            ->join('empresas as e', 'e.empresa', '=', 'ce.ruc')
            ->where('ce.Id', $idGuia)
            ->select(
                'ce.*',
                'ct.cmd_TaxSubtotal_TaxCategory_ID',
                'ct.cmd_TaxSubtotal_TaxCategory_Percent',
                'ct.cmd_TaxSubtotal_TaxCategory_TaxScheme_ID',
                'ct.cmd_TaxSubtotal_TaxCategory_TaxScheme_Name',
                'ct.cmd_TaxSubtotal_TaxCategory_TaxScheme_TaxTypeCode',
                'e.Direccion',
                'e.Ubigeo_emp',
                'e.Ubi_Departamento',
                'e.Ubi_Provincia',
                'e.Ubi_Distrito',
            )
            ->first();

        $despatch = DB::table('comprobantesdespatch as cd')
            ->where('cd.Id_Comprobantes', $idGuia)
            ->first();


        $detalles = DB::table('comprobantesd as cd')
            ->join('productos as p', 'cd.cod_Producto', '=', 'p.CodProducto')
            ->where('cd.id', $idGuia)
            ->get();
        $numeroItems = $detalles->count();


        $cuotas = DB::table('Comprobantes_paymentterms')
            ->where('Id_Comprobantes', $idGuia)
            ->get();

        $allowance = DB::table('comprobantes_allowancecharge as ca')
            ->where('ca.Id_Comprobantes', $idGuia)
            ->first();

        $leyendas = DB::table('comprobantesl')
            ->where('Id_Comprobantes', $idGuia)
            ->first();

        $see = require __DIR__ . '/config.php';

        // Cliente
        $client = (new Client())
            ->setTipoDoc($guiaRemisionObj->TipoDocIdR)
            ->setNumDoc($guiaRemisionObj->NumDocIdR)
            ->setRznSocial($guiaRemisionObj->RazonSocialR);

        // Emisor
        $address = (new Address())
            ->setUbigueo($guiaRemisionObj->Ubigeo_emp)
            ->setDepartamento($guiaRemisionObj->Ubi_Departamento)
            ->setProvincia($guiaRemisionObj->Ubi_Departamento)
            ->setDistrito($guiaRemisionObj->Ubi_Distrito)
            ->setUrbanizacion('-')
            ->setDireccion($guiaRemisionObj->Direccion)
            ->setCodLocal('0000'); // Codigo de establecimiento asignado por SUNAT, 0000 por defecto.

        $company = (new Company())
            ->setRuc($guiaRemisionObj->Ruc)
            ->setRazonSocial($guiaRemisionObj->RazonSocialE)
            ->setNombreComercial($guiaRemisionObj->RazonSocialE)
            ->setAddress($address);

        // Venta
        $invoice = (new Invoice())
            ->setUblVersion('2.1')
            ->setTipoOperacion($guiaRemisionObj->com_InvoiceTypeCode) // Venta - Catalog. 51
            ->setTipoDoc($guiaRemisionObj->TipoDocIdR) // Factura - Catalog. 01 
            ->setSerie($guiaRemisionObj->Serie)
            ->setCorrelativo($guiaRemisionObj->Numero)
            ->setFechaEmision(new DateTime($guiaRemisionObj->created_at)) // Zona horaria: Lima
            ->setFormaPago(new FormaPagoContado()) // FormaPago: Contado
            ->setTipoMoneda($guiaRemisionObj->TipoMoneda) // Sol - Catalog. 02
            ->setCompany($company)
            ->setClient($client)
            ->setMtoOperGravadas($guiaRemisionObj->BaseImponible)
            ->setMtoIGV($guiaRemisionObj->IGV)
            ->setTotalImpuestos($guiaRemisionObj->IGV)
            ->setValorVenta($guiaRemisionObj->BaseImponible)
            ->setSubTotal($guiaRemisionObj->ImporteTotal)
            ->setMtoImpVenta($guiaRemisionObj->ImporteTotal)
        ;

        $item = (new SaleDetail())
            ->setCodProducto($detalles->cod_Producto)
            ->setUnidad('NIU') // Unidad - Catalog. 03
            ->setCantidad($detalles->Cantidad)
            ->setMtoValorUnitario($detalles->ValorUnitario)
            ->setDescripcion($detalles->Descripcion)
            ->setMtoBaseIgv(100)
            ->setPorcentajeIgv(18.00) // 18%
            ->setIgv(18.00)
            ->setTipAfeIgv($detalles->cod_afectaigv) // Gravado Op. Onerosa - Catalog. 07
            ->setTotalImpuestos(18.00) // Suma de impuestos en el detalle
            ->setMtoValorVenta(100.00)
            ->setMtoPrecioUnitario($detalles->ValorUnitario)
        ;

        $legend = (new Legend())
            ->setCode($leyendas->ley_Codigo) // Monto en letras - Catalog. 52
            ->setValue($leyendas->ley_Texto);

        $invoice->setDetails([$item])
            ->setLegends([$legend]);

        $result = $see->send($invoice);

        // Guardar XML firmado digitalmente.
        file_put_contents(
            $invoice->getName() . '.xml',
            $see->getFactory()->getLastXml()
        );

        // Verificamos que la conexión con SUNAT fue exitosa.
        if (!$result->isSuccess()) {
            // Mostrar error al conectarse a SUNAT.
            echo 'Codigo Error: ' . $result->getError()->getCode();
            echo 'Mensaje Error: ' . $result->getError()->getMessage();
            exit();
        }

        // Guardamos el CDR
        file_put_contents('R-' . $invoice->getName() . '.zip', $result->getCdrZip());

        $cdr = $result->getCdrResponse();

        $code = (int) $cdr->getCode();

        if ($code === 0) {
            echo 'ESTADO: ACEPTADA' . PHP_EOL;
            if (count($cdr->getNotes()) > 0) {
                echo 'OBSERVACIONES:' . PHP_EOL;
                // Corregir estas observaciones en siguientes emisiones.
                var_dump($cdr->getNotes());
            }
        } else if ($code >= 2000 && $code <= 3999) {
            echo 'ESTADO: RECHAZADA' . PHP_EOL;
        } else {
            /* Esto no debería darse, pero si ocurre, es un CDR inválido que debería tratarse como un error-excepción. */
            /*code: 0100 a 1999 */
            echo 'Excepción';
        }

        echo $cdr->getDescription() . PHP_EOL;
        return true;
    }

}