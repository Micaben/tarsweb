<?php

namespace App\Http\Controllers;

use App\Models\Condicion;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
class CondicionController extends Controller
{
    public function mostrarMantenimiento()
    {
        return view('condicion');
    }

    public function listarDatosCondicion()
    {
        $medidas = Condicion::select(['id', 'descripcion','plazo'])->get();
        return response()->json($medidas);
    }

    public function guardarCondicion(Request $request)
    {
        try {
            $request->validate([
                'descripcion' => 'required',
                'plazo' => 'required',

            ]);
            $condicion = new Condicion;
            $condicion->descripcion = $request->input('descripcion');
            $condicion->plazo = $request->input('plazo');
            // Guardar en la base de datos
            
                $condicion->save();
                $id = $condicion->id;

                return response()->json(['success' => true, 'id' => $id]);
                          
        } catch (\Exception $e) {
            \Log::error('Error durante la creación del cliente: ' . $e->getMessage());
            \Log::error('Request data: ' . json_encode($request->all())); // Añadir esta línea
            return response()->json(['success' => false, 'errors' => $request->errors()->toArray()]);
        }
    }

    public function modificarCondicion(Request $request, $id)
    {
        try {
            // Validación de datos (puedes personalizarla según tus necesidades)
            $request->validate([
                'descripcion' => 'required',
                'plazo' => 'required',
            ]);
            $condicion = Condicion::find($id);
            // Verificar si el registro existe
            if (!$condicion) {
                return response()->json(['error' => 'Registro no encontrado'], 404);
            }

            // Actualizar los datos
            $condicion->descripcion = $request->input('descripcion');
            $condicion->plazo = $request->input('plazo');
            $condicion->save();
            $id = $condicion->id;

            return response()->json(['success' => true, 'id' => $id]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function obtenerDatosinputscondicion($id)
    {
        $datos = Condicion::find($id);
        return response()->json($datos);
    }

}
