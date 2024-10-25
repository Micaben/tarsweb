<?php

namespace App\Http\Controllers;

use App\Models\Movimiento;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
class MovimientoController extends Controller
{

    public function mostrarMantenimiento()
    {
        return view('motivomovimiento');
    }

    public function listarIngreso()
    {
        $mot = Movimiento::select(['id', 'tipo', 'descripcion', 'fe_shipment_handlingcode','concepto','estado'])
        ->where('tipo', 'I')
        ->get();
        return response()->json($mot);
    }

    public function listarSalida()
    {
        $mot = Movimiento::select(['id', 'tipo', 'descripcion'])
        ->where('tipo', 'S')
        ->get();
        return response()->json($mot);
    }

    public static function obtenertransaccion()
    {
        return DB::select("SELECT cod, deascripcion FROM tablavarios WHERE clase = '07'");
    }

    public static function obteneroperacion()
    {
        return DB::select("SELECT cod, deascripcion FROM tablavarios WHERE clase = 'T12'");
    }

    public static function obtenerconcepto()
    {
        return DB::select("SELECT id,Descripcion FROM conceptoventa WHERE  comprobante='01'");
    }

    public static function obtenermotivo()
    {
        return DB::select("SELECT id,Descripcion FROM conceptoventa WHERE  comprobante='01'");
    }

    public function guardarMotivos(Request $request)
    {
        try {
            $request->validate([
                'tipo' => 'required',
                'descripcion' => 'required',
                'transaccion' => 'required',
                'operacion' => 'required',
                'concepto' => 'required',
                'motivo' => 'required',
            ]);

            $motivos = new Movimiento;
            $motivos->tipo = $request->input('tipo');
            $motivos->descripcion = $request->input('descripcion');
            $motivos->trans = $request->input('transaccion');
            $motivos->tipoop = $request->input('operacion');
            $motivos->concepto = $request->input('concepto');
            $motivos->fe_shipment_handlingcode = $request->input('motivo');
            $motivos->estado = $request->has('estado');    
            // Guardar en la base de datos
            
                $motivos->save();
                $id = $motivos->id;

                return response()->json(['success' => true, 'id' => $id]);
                          
        } catch (\Exception $e) {
            \Log::error('Error durante la creación del usuario: ' . $e->getMessage());
            \Log::error('Request data: ' . json_encode($request->all())); // Añadir esta línea
            return response()->json(['success' => false, 'errors' => $request->errors()->toArray()]);
        }
    }

    public function modificarMotivos(Request $request, $id)
    {
        try {
            // Validación de datos (puedes personalizarla según tus necesidades)
            $request->validate([
                'tipo' => 'required',
                'descripcion' => 'required',
                'transaccion' => 'required',
                'operacion' => 'required',
                'concepto' => 'required',
                'motivo' => 'required',
            ]);

            $motivos = Movimiento::find($id);
            // Verificar si el registro existe
            if (!$motivos) {
                return response()->json(['error' => 'Registro no encontrado'], 404);
            }

            // Actualizar los datos
            $motivos->tipo = $request->input('tipo');
            $motivos->descripcion = $request->input('descripcion');
            $motivos->trans = $request->input('transaccion');
            $motivos->tipoop = $request->input('operacion');
            $motivos->concepto = $request->input('concepto');
            $motivos->fe_shipment_handlingcode = $request->input('motivo');
            $motivos->estado = $request->has('estado');  
            $motivos->save();
            $id = $motivos->id;

            return response()->json(['success' => true, 'id' => $id]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function obtenerDatosinputsmotivos($id)
    {
        $datos = Movimiento::find($id);
        return response()->json($datos);
    }

}