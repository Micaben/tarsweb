<?php

namespace App\Http\Controllers;

use App\Models\Conceptoventa;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
class ConceptoController extends Controller
{

    public function mostrarMantenimiento()
    {
        return view('conceptoventa');
    }

    public function listarConcepto()
    {
        $mot = Conceptoventa::select(['conceptoventa.id', 'conceptoventa.descripcion', 'conceptoventa.cortesia', 'conceptoventa.detalle', 'conceptoventa.kardex', 'conceptoventa.nogravada' ,'documentos.descripcion  as nombre_comprobante'])
        ->join('documentos', 'conceptoventa.comprobante', '=', 'documentos.documento')
        ->get();
        return response()->json($mot);
    }

    public function guardarConcepto(Request $request)
    {
        try {
            $request->validate([
                'nombres' => 'required',
                'dni' => '',
                'direccion' => '',
                'telefono' => '',
                'email' => '',                
            ]);

            $vendedor = new Conceptoventa;
            $vendedor->nombres = $request->input('nombres');
            $vendedor->dni = $request->input('dni');
            $vendedor->direccion = $request->input('direccion');
            $vendedor->telefono = $request->input('telefono');
            $vendedor->email = $request->input('email');
            $vendedor->activo = $request->has('estado');    
            // Guardar en la base de datos
            
                $vendedor->save();
                $id = $vendedor->id;

                return response()->json(['success' => true, 'id' => $id]);
                          
        } catch (\Exception $e) {
            \Log::error('Error durante la creación del usuario: ' . $e->getMessage());
            \Log::error('Request data: ' . json_encode($request->all())); // Añadir esta línea
            return response()->json(['success' => false, 'errors' => $request->errors()->toArray()]);
        }
    }

    public function modificarConcepto(Request $request, $id)
    {
        try {
            // Validación de datos (puedes personalizarla según tus necesidades)
            $request->validate([
                'tdescripcion' => 'required',
                'comprobante' => '',
                'tcuenta' => '',
                'tafectacion' => '',
                'tncnd' => '',  
                'toperacion' => '',
                'ttipofactura' => '',        
            ]);

            $concepto = Conceptoventa::find($id);
            // Verificar si el registro existe
            if (!$concepto) {
                return response()->json(['error' => 'Registro no encontrado'], 404);
            }

            // Actualizar los datos
            $concepto->descripcion = $request->input('tdescripcion');
            $concepto->comprobante = $request->input('comprobante');
            $concepto->cuenta = $request->input('tcuenta');
            
            $concepto->codafec = $request->input('tafectacion');
            $concepto->codncnd = $request->input('tncnd');    
            $concepto->tabla17 = $request->input('toperacion');
            $concepto->tabla51 = $request->input('ttipofactura'); 
            $concepto->cortesia = $request->has('tcortesia');   
            $concepto->detalle = $request->has('tdetalle');   
            $concepto->kardex = $request->has('tkardex');   
            $concepto->nogravada = $request->has('tgravada');      
            $concepto->save();
            $id = $concepto->id;

            return response()->json(['success' => true, 'id' => $id]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function obtenerDatosinputsconcepto($id)
    {
        $datos = Conceptoventa::find($id);
        return response()->json($datos);
    }

    public static function obtenerAfectacion()
    {
        return DB::select("SELECT cod, deascripcion FROM tablavarios WHERE clase = 'E07'");
    }
    public static function obtenerNC()
    {
        return DB::select("SELECT cod, deascripcion FROM tablavarios WHERE clase = 'E09'");
    }

    public static function obtenerND()
    {
        return DB::select("SELECT cod, deascripcion FROM tablavarios WHERE clase = 'E10'");
    }
    public static function obtenerOperacion()
    {
        return DB::select("SELECT cod, deascripcion FROM tablavarios WHERE clase = 'E17'");
    }
    public static function obtenerTipofactura()
    {
        return DB::select("SELECT cod, deascripcion FROM tablavarios WHERE clase = 'E51'");
    }

}