<?php

namespace App\Http\Controllers;

use App\Models\Umedida;
use App\Models\Color;
use App\Models\Marca;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class UmedidaController extends Controller
{

    public function mostrarMantenimiento()
    {
        return view('umedida');
    }

    public function obtenerdatosumedida()
    {
        $medidas = Umedida::all(); // Asegúrate de ajustar según tu modelo y tabla

        return response()->json($medidas);
    }

    public function listarDatosUmedida()
    {
        $medidas = Umedida::select(['id', 'umedida', 'descripcion'])->get();
        return response()->json($medidas);
    }

    public static function obtenerUmedidafe()
    {
        return DB::select("SELECT cod, deascripcion FROM tablavarios WHERE clase = 'E03'");
    }

    public static function obtenerUmedida()
    {
        return DB::select("SELECT cod, deascripcion FROM tablavarios WHERE clase = 'T6'");
    }

    public function guardarUnidad(Request $request)
    {
        try {
            // Validación de datos (puedes personalizarla según tus necesidades)
            $request->validate([
                'unidadmedida' => 'required',
                'descripcionumedida' => 'required',
                'umedida' => 'required',
                'umedidafe' => 'required',
            ]);
            $unidad = new Umedida;
            $unidad->umedida = $request->input('unidadmedida');
            $unidad->descripcion = $request->input('descripcionumedida');
            $unidad->codsunat = $request->input('umedida');
            $unidad->cod_int = $request->input('umedidafe');

            // Guardar en la base de datos
            $unidad->save();
            $id = $unidad->id;

            return response()->json(['success' => true, 'id' => $id]);
        } catch (\Exception $e) {
            \Log::error('Error durante la creación del usuario: ' . $e->getMessage());
            \Log::error('Request data: ' . json_encode($request->all())); // Añadir esta línea
            return response()->json(['success' => false, 'errors' => $request->errors()->toArray()]);
        }
    }

    public function modificarUnidad(Request $request, $id)
    {
        try {
            // Validación de datos (puedes personalizarla según tus necesidades)
            $request->validate([
                'codigoumedida' => 'required',
                'unidadmedida' => 'required',
                'descripcionumedida' => 'required',
                'umedida' => 'required',
                'umedidafe' => 'required',
            ]);

            // Buscar el registro por su ID
            $unidad = Umedida::find($id);

            // Verificar si el registro existe
            if (!$unidad) {
                return response()->json(['error' => 'Registro no encontrado'], 404);
            }

            // Actualizar los datos
            $unidad->id = $request->input('codigoumedida');
            $unidad->umedida = $request->input('unidadmedida');
            $unidad->descripcion = $request->input('descripcionumedida');
            $unidad->codsunat = $request->input('umedida');
            $unidad->cod_int = $request->input('umedidafe');

            // Guardar en la base de datos
            $unidad->save();
            $id = $unidad->id;

            return response()->json(['success' => true, 'id' => $id]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function obtenerDatos($id)
    {
        $datos = Umedida::find($id);
        return response()->json($datos);
    }

    public function listarDatosColor()
    {
        $medidas = Color::select(['id', 'descripcion'])->get();
        return response()->json($medidas);
    }

    public function guardarColor(Request $request)
    {
        try {
            // Validación de datos (puedes personalizarla según tus necesidades)
            $request->validate([
                'descripcioncolor' => 'required',
            ]);
            $color = new Color;
            $color->descripcion = $request->input('descripcioncolor');

            // Guardar en la base de datos
            $color->save();
            $id = $color->id;

            return response()->json(['success' => true, 'id' => $id]);
        } catch (\Exception $e) {
            \Log::error('Error durante la creación del usuario: ' . $e->getMessage());
            \Log::error('Request data: ' . json_encode($request->all())); // Añadir esta línea
            return response()->json(['success' => false, 'errors' => $request->errors()->toArray()]);
        }
    }

    public function modificarColor(Request $request, $id)
    {
        try {
            // Validación de datos (puedes personalizarla según tus necesidades)
            $request->validate([
                'codigocolor' => 'required',
                'descripcioncolor' => 'required',
            ]);

            // Buscar el registro por su ID
            $color = Color::find($id);

            // Verificar si el registro existe
            if (!$color) {
                return response()->json(['error' => 'Registro no encontrado'], 404);
            }

            // Actualizar los datos
            $color->id = $request->input('codigocolor');
            $color->descripcion = $request->input('descripcioncolor');

            // Guardar en la base de datos
            $color->save();
            $id = $color->id;

            return response()->json(['success' => true, 'id' => $id]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function listarDatosMarca()
    {
        $marca = Marca::select(['id', 'descripcion'])->get();
        return response()->json($marca);
    }

    public function guardarMarca(Request $request)
    {
        try {
            // Validación de datos (puedes personalizarla según tus necesidades)
            $request->validate([
                'descripcionmarca' => 'required',
            ]);
            $marca = new Marca;
            $marca->descripcion = $request->input('descripcionmarca');

            // Guardar en la base de datos
            $marca->save();
            $id = $marca->id;

            return response()->json(['success' => true, 'id' => $id]);
        } catch (\Exception $e) {
            \Log::error('Error durante la creación del usuario: ' . $e->getMessage());
            \Log::error('Request data: ' . json_encode($request->all())); // Añadir esta línea
            return response()->json(['success' => false, 'errors' => $request->errors()->toArray()]);
        }
    }

    public function modificarMarca(Request $request, $id)
    {
        try {
            // Validación de datos (puedes personalizarla según tus necesidades)
            $request->validate([
                'codigomarca' => 'required',
                'descripcionmarca' => 'required',
            ]);

            // Buscar el registro por su ID
            $marca = marca::find($id);

            // Verificar si el registro existe
            if (!$marca) {
                return response()->json(['error' => 'Registro no encontrado'], 404);
            }

            // Actualizar los datos
            $marca->id = $request->input('codigomarca');
            $marca->descripcion = $request->input('descripcionmarca');

            // Guardar en la base de datos
            $marca->save();
            $id = $marca->id;

            return response()->json(['success' => true, 'id' => $id]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

}