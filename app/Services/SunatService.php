<?php
namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use SoapClient;
use SoapFault;
use Datetime;
use DOMDocument;
class SunatService
{
    const SUNAT_SEND_API_ENDPOINT = 'https://api-cpe.sunat.gob.pe/v1/contribuyente/gem/comprobantes/';
    const SUNAT_SEND_API_ENDPOINT_TEST = "https://gre-test.nubefact.com/v1/contribuyente/gem/comprobantes/";
    const SUNAT_CONSULT_API_ENDPOINT = 'https://api-cpe.sunat.gob.pe/v1/contribuyente/gem/comprobantes/envios/';
    const SUNAT_CONSULT_API_ENDPOINT_TEST = "https://gre-test.nubefact.com/v1/contribuyente/gem/comprobantes/envios/";

    public $mensajeError;
    public $coderror;
    public $xml;
    public $xmlb64;
    public $cdrb64;
    public $codrespuesta;

    public $code;

    public function enviarComprobante($fecha, $empresaRUC, $tipo, $serie, $numero)
    {
        // Inicializa variables
        $fichero = $empresaRUC . "-" . $tipo . "-" . $serie . "-" . $numero;
        $Documento = $tipo . "-" . $serie . "-" . $numero;
        $fechaConvertida = DateTime::createFromFormat('Y-m-d', $fecha)->format('Ymd');
        $pdfDHRes = null;
        //$carpeta = $this->getRutaXML($tipo) . $codCliente . "/";
        $rutaArchivo = public_path("/storage/Facturas/XML/{$fechaConvertida}/{$fichero}.zip");
        $rutaRespuesta = public_path("/storage/XML/{$fechaConvertida}/{$fichero}_Respuesta.zip");
        $nombreRespuesta = "{$fichero}_Respuesta";

        try {
            // Crear archivo ZIP y enviar la factura a través de sendBill
            $pdfDHRes = $this->sendBill("{$fichero}.zip", $rutaArchivo, $empresaRUC, $Documento);

            // Guarda la respuesta ZIP si existe
            if ($pdfDHRes) {
                file_put_contents($rutaRespuesta, $pdfDHRes);
            }
        } catch (\Exception $e) {
            // Log del error en caso de falla
            \Log::error("Error al enviar comprobante: " . $e->getMessage());
            return null;
        }

        return $nombreRespuesta;
    }

    private function sendBill($fileName, $filePath, $empresaRUC, $Documento)
    {
        try {
            // Inicializa configuración del cliente SOAP
            $soapClient = $this->getSoapClient($empresaRUC, $Documento);
            $fileContent = file_get_contents($filePath);

            // Llamar al servicio de SUNAT con el archivo ZIP
            return $soapClient->__soapCall('sendBill', [
                'fileName' => $fileName,
                'contentFile' => base64_encode($fileContent)  // Codificación requerida
            ]);
        } catch (SoapFault $e) {
            // Captura errores de conexión o SOAP
            \Log::error("Error SOAP: " . $e->getMessage());
            return null;
        }
    }

    private function getSoapClient($empresaRUC, $Documento)
    {
        $wsdlUrl = "https://e-beta.sunat.gob.pe/ol-ti-itcpfegem-beta/billService?wsdl";  // URL del WSDL de SUNAT
        $options = [
            'trace' => true,
            'exceptions' => true,
            'cache_wsdl' => WSDL_CACHE_NONE,
            'soap_version' => SOAP_1_2,
            'stream_context' => stream_context_create([
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                ],
            ]),
        ];

        $soapClient = new SoapClient($wsdlUrl, $options);

        // Configura el header de autenticación
        $authHeader = $this->createAuthHeader($empresaRUC, $Documento);
        $soapClient->__setSoapHeaders($authHeader);

        return $soapClient;
    }

    private function createAuthHeader($empresaRUC, $Documento)
    {
        $usuario = "20100066603MODDATOS"; // Reemplaza con tu usuario
        $clave = "moddatos"; // Reemplaza con tu clave
        $xml = new DOMDocument('1.0', 'UTF-8');

        // Crear nodo de autenticación WSSE
        $security = $xml->createElementNS('http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd', 'wsse:Security');
        $usernameToken = $xml->createElement('wsse:UsernameToken');
        $username = $xml->createElement('wsse:Username', $usuario);
        $password = $xml->createElement('wsse:Password', $clave);

        $usernameToken->appendChild($username);
        $usernameToken->appendChild($password);
        $security->appendChild($usernameToken);
        $xml->appendChild($security);

        // Devuelve el header como un objeto SoapHeader
        return new \SoapHeader('http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd', 'Security', new \SoapVar($xml->saveXML(), XSD_ANYXML));
    }

    public function EnviarComprobanteElectronico1($fecha, $ruc, $tipo, $serie, $numero)
    {
        //if ($emisor['modo'] == 'n') {
        $usuario_sol = '20100066603MODDATOS';
        $clave_sol = 'moddatos';
        $certificado = storage_path('certificates/certificate.pem');
        $wsS = 'https://e-beta.sunat.gob.pe/ol-ti-itcpfegem-beta/billService?wsdl';
        $pass_certificado = 'userfact2022';
        //}
        //if ($emisor['modo'] == 's') {
        //$usuario_sol = '20100066603MODDATOS';
        //	$clave_sol = 'moddatos';
        //$certificado = storage_path('certificates/certificate.pem');
        //$wsS = 'https://e-factura.sunat.gob.pe/ol-ti-itcpfegem/billService?wsdl';
        //$pass_certificado = 'userfact2022';
        //}   
        $fechaConvertida = DateTime::createFromFormat('Y-m-d', $fecha)->format('Ymd');
        //$objfirma = new Signature();
        // $flg_firma = 0; //Posicion del XML: 0 para firma

        $fechaActual = date('Ymd');
        $nombreArchivoXML = session('empresa') . '-' . $tipo . '-' . $serie . '-' . $numero . '.xml';
        $nombreArchivoZIP = session('empresa') . '-' . $tipo . '-' . $serie . '-' . $numero . '.zip';
        $rutaCarpetaXML = public_path('/storage/Facturas/XML/' . $fechaActual);
        $directorio = public_path("/storage/XML/{$fechaConvertida}");
        $rutaCompletaXML = $rutaCarpetaXML . '/' . $nombreArchivoXML;
        $certificado = storage_path('certificates/certificate.pem'); //ruta del archivo del certicado para firmar
        $pass_firma = $pass_certificado;

        //$resp = $objfirma->signature_xml($flg_firma, $rutaCompletaXML, $certificado, $pass_firma);
        //firma----------------------------------------------------------------
        //print_r($this->hash = $resp);
        //echo '</br> XML FIRMADO';
        $this->xml = $nombreArchivoXML;
        //FIRMAR XML - FIN

        //CONVERTIR A ZIP - INICIO
        //$zip = new \ZipArchive;


        $rutazip = public_path("/storage/Facturas/XML/{$fechaConvertida}/{$ruc}-{$tipo}-{$serie}-{$numero}.zip");

        //if ($zip->open($rutazip, \ZipArchive::CREATE) === TRUE) {
        //	$zip->addFile($rutaCarpetaXML, $nombreArchivoXML);
        //	$zip->close();
        //}

        // echo '</br>XML ZIPEADO';
        //CONVERTIR A ZIP - FIN
        //ENVIAR EL ZIP A LOS WS DE SUNAT - INICIO
        $ws = $wsS; //ruta del servicio web de pruebad e SUNAT para enviar documentos
        //$ruta_archivo = $rutazip;


        $contenido_del_zip = base64_encode(file_get_contents($rutazip)); //codificar y convertir en texto el .zip

        //echo '</br> '. $contenido_del_zip;
        $xml_envio = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ser="http://service.sunat.gob.pe" xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd">
                        <soapenv:Header>
                        <wsse:Security>
                            <wsse:UsernameToken>
                                <wsse:Username>' . $usuario_sol . '</wsse:Username>
                                <wsse:Password>' . $clave_sol . '</wsse:Password>
                            </wsse:UsernameToken>
                        </wsse:Security>
                        </soapenv:Header>
                        <soapenv:Body>
                        <ser:sendBill>
                            <fileName>' . $nombreArchivoZIP . '</fileName>
                            <contentFile>' . $contenido_del_zip . '</contentFile>
                        </ser:sendBill>
                        </soapenv:Body>
                    </soapenv:Envelope>';

        $header = array(
            "Content-type: text/xml; charset=\"utf-8\"",
            "Accept: text/xml",
            "Cache-Control: no-cache",
            "Pragma: no-cache",
            "SOAPAction: ",
            "Content-lenght: " . strlen($xml_envio)
        );

        $ch = curl_init(); //iniciar la llamada
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1); //
        curl_setopt($ch, CURLOPT_URL, $ws);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_envio);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

        //para ejecutar los procesos de forma local en windows
        //enlace de descarga del cacert.pem https://curl.haxx.se/docs/caextract.html
        curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__) . "/cacert.pem"); //solo en local, si estas en el servidor web con ssl comentar esta línea

        $response = curl_exec($ch); // ejecucion del llamado y respuesta del WS SUNAT.

        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE); // objten el codigo de respuesta de la peticion al WS SUNAT
        $estadofe = "0"; //inicializo estado de operación interno

        if ($httpcode == 200) //200: La comunicacion fue satisfactoria
        {
            $doc = new DOMDocument(); //clase que nos permite crear documentos XML
            $doc->loadXML($response); //cargar y crear el XML por medio de text-xml response

            if (isset($doc->getElementsByTagName('applicationResponse')->item(0)->nodeValue)) // si en la etique de rpta hay valor entra
            {

                $cdr = $doc->getElementsByTagName('applicationResponse')->item(0)->nodeValue; //guadarmos la respuesta(text-xml) en la variable 

                $cdr = base64_decode($cdr); //decodificando el xml
                file_put_contents($rutaCarpetaXML . 'R-' . $nombreArchivoZIP, $cdr); //guardo el CDR zip en la carpeta cdr

                $this->cdrb64 = "R-" . $nombreArchivoZIP;
                if (!is_dir($directorio)) {
                    mkdir($directorio, 0755, true);
                }
                $zip = new \ZipArchive;
                if ($zip->open($rutaCarpetaXML . 'R-' . $nombreArchivoZIP) === TRUE) {
                    $zip->extractTo($directorio, 'R-' . $nombreArchivoXML);
                    $zip->close();

                    $this->xmlb64 = "R-" . $nombreArchivoXML;
                }

                $xml_decode = file_get_contents($rutaCarpetaXML . 'R-' . $nombreArchivoXML) or die("Error: Cannot create object");

                // Obteniendo datos del archivo .XML
                $ResponseCode = "";
                $DOM = new DOMDocument('1.0', 'ISO-8859-1');
                $DOM->preserveWhiteSpace = FALSE;
                $DOM->loadXML($xml_decode);

                // Obteniendo RUC.
                $DocXML = $DOM->getElementsByTagName('ResponseCode');
                foreach ($DocXML as $Nodo) {
                    $ResponseCode = $Nodo->nodeValue;
                }

                $DocXML = $DOM->getElementsByTagName('Description');
                foreach ($DocXML as $Nodo) {
                    $description = $Nodo->nodeValue;
                }
                $DocXML = $DOM->getElementsByTagName('ResponseDate');
                foreach ($DocXML as $Nodo) {
                    $fecha3 = $Nodo->nodeValue;
                }
                $pos = $ResponseCode;
                //=============hash CDR=================
                $doc_cdr = new DOMDocument();
                $doc_cdr->load($rutaCarpetaXML . 'R-' . $nombreArchivoXML);
                $hash_cdr = $doc_cdr->getElementsByTagName('DigestValue')->item(0)->nodeValue;

                if ($pos == 0) {
                    $estadofe = 1;
                } else {
                    $estadofe = $pos;
                }


                echo '<div class="btnsuccess">' . $description . ' por Sunat</div>';

                $this->codrespuesta = $estadofe;
            } else {

                $estadofe = '2';
                $codigo = $doc->getElementsByTagName('faultcode')->item(0)->nodeValue;
                $mensaje = $doc->getElementsByTagName('faultstring')->item(0)->nodeValue;
                //LOG DE TRAX ERRORES DB
                $code = preg_replace('/[^0-9]/', '', $codigo);
                if ($code >= 2000 && $code <= 3999) {
                    $this->coderror = $codigo;
                    $this->mensajeError = $mensaje;
                    $this->codrespuesta = $estadofe;
                    $this->code = $code;
                } else {
                    // echo 'Ocurrio un error con código: ' . $codigo . ' Msje:' . $mensaje;
                    $this->coderror = '';
                    $this->mensajeError = '';
                    $this->codrespuesta = 3;
                    $this->code = $code;
                }
            }
        } else { //Problemas de comunicacion
            $estadofe = "3";
            //LOG DE TRAX ERRORES DB
            echo curl_error($ch);
            echo "<script>
			Swal.fire({
				title: 'Existe un problema de conexión',
				text: '¡OJO!',
				html: `<h4>El comprobante ya fue registrado y se encuentra en <a href='ventas'>Administrar ventas</a>, puede enviarlo cuando se restablezca su conexión</h4>`,
				icon: 'warning',			
				showCancelButton: true,
				showConfirmButton: false,
				allowOutsideClick: false,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				cancelButtonText: 'Cerrar',
			})
			</script>";
            $this->codrespuesta = $estadofe;
        }

        curl_close($ch);
        //ENVIAR EL ZIP A LOS WS DE SUNAT - FIN
        return true;
    }

    public function EnviarComprobanteElectronico($fecha, $ruc, $tipo, $serie, $numero)
{
    $usuario_sol = '104205068500MODDATOS';
    $clave_sol = 'moddatos';
    $certificado = storage_path('certificates/certificate.pem');
    $wsS = 'https://e-beta.sunat.gob.pe/ol-ti-itcpfegem-beta/billService';
    $pass_certificado = 'userfact2022';	 
    
    // Otros parámetros y configuraciones...
    $fechaConvertida = DateTime::createFromFormat('Y-m-d', $fecha)->format('Ymd');
    $nombreArchivoZIP = session('empresa') . '-' . $tipo . '-' . $serie . '-' . $numero . '.zip';
    $rutaCompletaZIP = public_path("storage/Facturas/XML/{$fechaConvertida}/{$ruc}-{$tipo}-{$serie}-{$numero}.zip");
    
    // Leer y codificar el archivo ZIP en base64
    if (!file_exists($rutaCompletaZIP)) {
        Log::error("El archivo ZIP no existe en la ruta especificada: {$rutaCompletaZIP}");
        return response()->json(['error' => "El archivo ZIP no existe en la ruta especificada."]);
    }
    Log::error("ruta especificada: {$rutaCompletaZIP}");   
    $contenido_del_zip = base64_encode(file_get_contents($rutaCompletaZIP));

    $xml_envio = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ser="http://service.sunat.gob.pe" xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd">
                        <soapenv:Header>
                        <wsse:Security>
                            <wsse:UsernameToken>
                                <wsse:Username>' . $usuario_sol . '</wsse:Username>
                                <wsse:Password>' . $clave_sol . '</wsse:Password>
                            </wsse:UsernameToken>
                        </wsse:Security>
                        </soapenv:Header>
                        <soapenv:Body>
                        <ser:sendBill>
                            <fileName>' . $nombreArchivoZIP . '</fileName>
                            <contentFile>cid:' . $contenido_del_zip . '</contentFile>
                        </ser:sendBill>
                        </soapenv:Body>
                    </soapenv:Envelope>';

                    $header = array(
                        "Content-type: text/xml; charset=\"utf-8\"",
                        "Accept: text/xml",
                        "Cache-Control: no-cache",
                        "Pragma: no-cache",
                        "SOAPAction: \"\"",
                        "Content-length: " . strlen($xml_envio)
                    );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
    curl_setopt($ch, CURLOPT_URL, $wsS);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_envio);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    curl_setopt($ch, CURLOPT_VERBOSE, true);  // Activa la salida de depuración
    curl_setopt($ch, CURLOPT_CAINFO,  storage_path('certificates/cacert.pem')); 
    //curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__) . "/cacert.pem");                    
    $response = curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                    
    if ($response === false) {
        Log::error("cURL Error: " . curl_error($ch));
        curl_close($ch);
        return response()->json(['error' => "Error en la conexión: " . curl_error($ch)]);
    }

    curl_close($ch);

    if ($httpcode == 200) {
        libxml_use_internal_errors(true);
        $doc = new DOMDocument();
        $xmlLoaded = $doc->loadXML($response);

        if ($xmlLoaded) {
            $applicationResponseNode = $doc->getElementsByTagName('applicationResponse')->item(0);
            if ($applicationResponseNode) {
                $cdr = base64_decode($applicationResponseNode->nodeValue);
                // Procesar el CDR o guardar como necesites
            } else {
                Log::error("El XML no contiene el nodo 'applicationResponse'.");
                return response()->json(['error' => "El XML no contiene el nodo 'applicationResponse'."]);
            }
        } else {
            Log::error("Error en el formato de respuesta de SUNAT: {$response}");
            return response()->json(['error' => "Error en el formato de respuesta de SUNAT."]);
        }
    } else {
        Log::error("Error al procesar la solicitud. Código de estado: $httpcode, Respuesta: $response");
        return response()->json(['error' => "Error al procesar la solicitud. Código de estado: $httpcode"]);
    }

    return response()->json(['success' => true]);
}
}