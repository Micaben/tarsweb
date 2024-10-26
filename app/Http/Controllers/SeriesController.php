<?php

namespace App\Http\Controllers;

use App\Models\Series;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class SeriesController extends Controller
{

    public function obtenerSeries($id)
    {
        $serie = Series::select(['id', 'serie', 'ultimo', 'activo'])
            ->where('td', $id)
            ->get();
        return response()->json($serie);
    }

    public function obtenerNumeros($td, $id)
    {
        try {
            $sql = "SELECT COALESCE(MAX(Ultimo), 0) + 1 AS ultimo FROM SerieDoc WHERE TD = ? AND id = ?";
            $result = DB::select($sql, [$td, $id]);
            
            if (!empty($result) && isset($result[0]->ultimo)) {
                return response()->json($result);
            } else {
                throw new \Exception("No se pudo obtener el número de la base de datos.");
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function obtenerDatosinputsseries($id)
    {
        $datos = Series::find($id);
        return response()->json($datos);
    }

    public function guardarSeries(Request $request)
    {
        try {
            $request->validate([
                'tdocumento' => 'required',
                'serie' => 'required',
                'ultimo' => 'required',
                'diario' => '',
                'cuenta' => '',

            ]);

            $series = new Series;
            $empresa = session('ruc_empresa');
            $series->td = $request->input('tipodocumento');
            $series->serie = $request->input('documento');
            $series->ultimo = $request->input('abrev');
            $series->diario = $request->input('factor');
            $series->empresa = $empresa;
            $series->activo = $request->has('estado');


            // Guardar en la base de datos

            $series->save();
            $id = $series->id;

            return response()->json(['success' => true, 'id' => $id]);

        } catch (\Exception $e) {
            \Log::error('Error durante la creación del usuario: ' . $e->getMessage());
            \Log::error('Request data: ' . json_encode($request->all())); // Añadir esta línea
            return response()->json(['success' => false, 'errors' => $request->errors()->toArray()]);
        }
    }

    public function modificarSeries(Request $request, $id)
    {
        try {
            // Validación de datos (puedes personalizarla según tus necesidades)
            $request->validate([
                'tddedocumento' => 'required',
                'serie' => 'required',
                'ultimo' => 'required',
                'diario' => '',
            ]);

            $series = Series::find($id);
            $empresa = session('empresa');
            // Verificar si el registro existe
            if (!$series) {
                return response()->json(['error' => 'Registro no encontrado'], 404);
            }

            // Actualizar los datos           
            $series->td = $request->input('tddedocumento');
            $series->serie = $request->input('serie');
            $series->ultimo = $request->input('ultimo');
            $series->diario = $request->input('diario');
            $series->empresa = $empresa;
            $series->activo = $request->has('estado');
            $series->save();
            $id = $series->id;

            return response()->json(['success' => true, 'id' => $id]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}