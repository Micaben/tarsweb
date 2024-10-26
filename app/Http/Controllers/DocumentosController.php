<?php

namespace App\Http\Controllers;

use App\Models\Documentos;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class DocumentosController extends Controller
{

    public function mostrarMantenimiento()
    {
        return view('documentos');
    }

    public function obtenerdocumentos()
    {
        $medidas = Documentos::all(); // Asegúrate de ajustar según tu modelo y tabla

        return response()->json($medidas);
    }

    public function listarDocumentos()
    {
        $medidas = Documentos::select(['id', 'documento', 'descripcion','abrev','factor','cuentas','factbol'])->get();
        return response()->json($medidas);
    }

    public function guardarDocumentos(Request $request)
    {
        try {
            $request->validate([
                'tipodocumento' => 'required',
                'documento' => 'required',
                'abrev' => '',
                'factor' => '',
                'cuenta' => '',

            ]);

            $documentos = new Documentos;
            $documentos->documento = $request->input('tipodocumento');
            $documentos->descripcion = $request->input('documento');
            $documentos->abrev = $request->input('abrev');
            $documentos->factor = $request->input('factor');
            $documentos->cuentas = $request->input('cuenta');
            $documentos->factbol = $request->has('facturable');
            

            // Guardar en la base de datos
            
                $documentos->save();
                $id = $documentos->id;

                return response()->json(['success' => true, 'id' => $id]);
                          
        } catch (\Exception $e) {
            \Log::error('Error durante la creación del usuario: ' . $e->getMessage());
            \Log::error('Request data: ' . json_encode($request->all())); // Añadir esta línea
            return response()->json(['success' => false, 'errors' => $request->errors()->toArray()]);
        }
    }

    public function modificarDocumentos(Request $request, $id)
    {
        try {
            // Validación de datos (puedes personalizarla según tus necesidades)
            $request->validate([
                'tipodocumento' => 'required',
                'documento' => 'required',
                'abrev' => '',
                'factor' => '',
                'cuenta' => '',
            ]);

            $documentos = Documentos::find($id);
            // Verificar si el registro existe
            if (!$documentos) {
                return response()->json(['error' => 'Registro no encontrado'], 404);
            }

            // Actualizar los datos
            $documentos->documento = $request->input('tipodocumento');
            $documentos->descripcion = $request->input('documento');
            $documentos->abrev = $request->input('abrev');
            $documentos->factor = $request->input('factor');
            $documentos->cuentas = $request->input('cuenta');
            $documentos->factbol = $request->has('facturable');
            $documentos->save();
            $id = $documentos->id;

            return response()->json(['success' => true, 'id' => $id]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function obtenerDatosinputsdocumentos($id)
    {
        $datos = Documentos::find($id);
        return response()->json($datos);
    }
}