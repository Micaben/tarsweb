<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\EnviarDocumentoCorreo;

class CorreoController extends Controller
{
    public function enviarDocumentoPorCorreo(Request $request)
    {
        $identpedidoId = $request->input('identpedidoId');
        $archivoPdf = $request->input('archivoPdf'); // La ruta del PDF
        $correoDestino = $request->input('correo');  // La dirección de correo enviada desde el formulario
        if (!$identpedidoId || !$archivoPdf || !$correoDestino) {
            return response()->json(['success' => false, 'message' => 'Faltan parámetros necesarios.']);
        }

        try {
            //\Log::info("Intentando enviar el correo a $correoDestino con el archivo $archivoPdf");        
            Mail::send([], [], function ($message) use ($archivoPdf, $correoDestino) {
                $message->from('sistemas@grupovga.com', 'Mycsoft')
                    ->to($correoDestino) // Usar el correo pasado en el formulario                
                    ->subject('Guia electrónica '. basename($archivoPdf))
                    ->attach(public_path($archivoPdf)) // Adjuntar el archivo PDF
                    ->html('<p>Aquí tienes el documento que solicitaste.</p>');
                    //->setBody('Aquí tienes el documento que solicitaste.');
            });            
            return response()->json(['success' => true, 'message' => 'Correo enviado correctamente.']);
        } catch (\Exception $e) {
            \Log::error("Error al enviar el correo: " . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error al enviar el correo: ' . $e->getMessage()]);
        }
    }
}