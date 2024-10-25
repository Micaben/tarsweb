<?php

namespace App\Http\Controllers;

use App\Models\Tipocambio;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
class TipocambioController extends Controller
{

    public function mostrarMantenimiento()
    {
        return view('tipocambio');
    }

    public function listarDatosTipocambio(Request $request)
    {
        $mes = $request->query('mes');
        $anio = $request->query('anio');
        $datosTipoCambio = Tipocambio::whereYear('fecha', $anio)
            ->whereMonth('fecha', $mes)
            ->selectRaw('id, DATE_FORMAT(fecha, "%d/%m/%Y") AS fecha_formateada, compra, venta, comercial')
        ->get();     
        return response()->json($datosTipoCambio);
    }

    public function guardarTipocambio(Request $request)
    {
        try {
            $request->validate([
                'fecha' => 'required',
                'compratc' => 'required',
                'ventatc' => 'required',
                'comercialtc' => 'required',
            ]);

            $tipocambio = new Tipocambio;
            $tipocambio->fecha = $request->input('fecha');
            $tipocambio->compra = $request->input('compratc');
            $tipocambio->venta = $request->input('ventatc');
            $tipocambio->comercial = $request->input('comercialtc');
            // Guardar en la base de datos

            $tipocambio->save();
            $id = $tipocambio->id;

            return response()->json(['success' => true, 'id' => $id]);

        } catch (\Exception $e) {
            \Log::error('Error al guardar registro del tipo de cambio: ' . $e->getMessage());
            \Log::error('Request data: ' . json_encode($request->all())); // Añadir esta línea
            return response()->json(['success' => false, 'message' => 'El registro ya existe']);
        }
    }

    public function modificarTipocambio(Request $request, $id)
    {
        try {
            // Validación de datos (puedes personalizarla según tus necesidades)
            $request->validate([
                'fecha' => 'required',
                'compratc' => 'required',
                'ventatc' => 'required',
                'comercialtc' => 'required',                
            ]);

            $tipocambio = Tipocambio::find($id);
            // Verificar si el registro existe
            if (!$tipocambio) {
                return response()->json(['error' => 'Registro no encontrado'], 404);
            }

            // Actualizar los datos
            $tipocambio->fecha = $request->input('fecha');
            $tipocambio->compra = $request->input('compratc');
            $tipocambio->venta = $request->input('ventatc');
            $tipocambio->comercial = $request->input('comercialtc');
            $tipocambio->save();
            $id = $tipocambio->id;

            return response()->json(['success' => true, 'id' => $id]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function obtenerDatosinputstipocambio($id)
    {
        $datos = Tipocambio::find($id);
        return response()->json($datos);
    }

    public function consultawebTC(Request $request)
    {
        $fecha = $request->fecha;
        $apiUrl = "https://api.apis.net.pe/v1/tipo-cambio-sunat?date=" . $fecha;

        try {
            $response = Http::withHeaders([
                'Referer' => 'https://apis.net.pe/tipo-de-cambio-sunat-api',
                'Authorization' => 'Bearer apis-token-1.aTSI1U7KEuT-6bbbCguH-4Y8TI6KS73N',
            ])->get($apiUrl);

            return $response->json(); // Devuelve la respuesta JSON de la API
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500); // Maneja cualquier error
        }
    }

    public function validarDatos(Request $request)
    {
        // Obtener la fecha enviada en la solicitud
        $fechaSolicitud = $request->input('fecha');
    
        // Verificar si ya existe un registro con la misma fecha en la base de datos
        $registroExistente = Tipocambio::whereDate('fecha', $fechaSolicitud)->exists();
    
        // Devolver una respuesta JSON indicando si ya existe un registro para la fecha solicitada
        return response()->json(['registroExistente' => $registroExistente]);
    }

    public function mostrarConsulta($fecha)
    {
        $tipoCambio = TipoCambio::where('fecha', $fecha)->first();

        // Luego puedes devolver los datos como una respuesta JSON o como prefieras
        return response()->json($tipoCambio);
    }
}