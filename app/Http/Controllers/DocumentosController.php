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
        $medidas = Documentos::select(['id', 'documento', 'descripcion', 'abrev', 'factor', 'cuentas', 'factbol'])->get();
        return response()->json($medidas);
    }

    public function listarDocumentosFac()
    {
        // Filtra los documentos donde factbol sea igual a 1
        $medidas = Documentos::select(['id', 'documento', 'descripcion'])
            ->where('factbol', 1)
            ->get();

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

    public function buscarFactura($tipod, $serie, $numero)
    {
        try {
            $query = "select b.ruc,b.RazonSocial,a.Fechaemision,
            a.Moneda,a.NumOCompra, a.ImporteTotal,a.BaseImponible,a.igv,
            tv.Factor2,a.vendedor,
            tv.Deascripcion,a.id
            from comprobantesefact a inner join Cliente b on b.ruc=a.NumDocIdR
             left join vendedores v on v.id=a.vendedor 
             left join TablaVarios tv on a.Moneda=tv.cod and tv.clase ='MON' 
            where a.tipodocumento=? and a.serie=? and numero=?";

            $result = DB::select($query, [$tipod, $serie, $numero]);

            if (!empty($result)) {
                return response()->json(['success' => true, 'data' => $result[0]]);
            } else {
                return response()->json(['success' => false, 'message' => 'No se encontró ninguna guía con el ID proporcionado.']);
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error en la consulta de la guía: ' . $e->getMessage());
        }
    }


}