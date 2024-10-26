<?php

namespace App\Http\Controllers;

use App\Models\Linea;
use App\Models\Sublinea;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class LineaController extends Controller
{

    public function mostrarMantenimiento()
    {
        return view('linea');
    }

    public function obtenerdatos()
    {
        $lineas = Linea::all(); // Asegúrate de ajustar según tu modelo y tabla

        return response()->json($lineas);
    }

    public function listarDatoslinea()
    {
        $linea = Linea::select(['id', 'descripcion', 'ctaVentas', 'ctaCompras', 'ctaMercaderias','costoVentas','ctaExistencias'])->get();
        return response()->json($linea);
    }

    public static function obtenerLinea()
    {
        $lineas = Linea::all(); // Asegúrate de ajustar según tu modelo y tabla

        return response()->json($lineas);
    }

    public function guardarLinea(Request $request)
    {
        try {
            $request->validate([
                'descripcionlinea' => 'required',
                'cuentaventas' => '',
                'cuentacompras' => '',
                'costoventas' => '',
                'mercaderia' => '',
                'existencia' => '',

            ]);
            $lineas = new linea;
            $lineas->descripcion = $request->input('descripcionlinea');
            $lineas->ctaventas = $request->input('cuentaventas');
            $lineas->ctaCompras = $request->input('cuentacompras');            
            $lineas->ctamercaderias = $request->input('mercaderia');
            $lineas->costoventas = $request->input('costoventas');
            $lineas->ctaexistencias = $request->input('existencia');

            // Guardar en la base de datos
            $lineas->save();
            $id = $lineas->id;

            return response()->json(['success' => true, 'id' => $id], 200);
        } catch (\Exception $e) {
            \Log::error('Error: ' . $e->getMessage());
            \Log::error('Request data: ' . json_encode($request->all())); // Add this line    
            // You might want to return a different status code based on the error type
            return response()->json(['success' => false, 'errors' => $request->errors()->toArray()], 500);
        }
    }

        public function modificarLinea(Request $request, $id)
        {
            try {
                // Validación de datos (puedes personalizarla según tus necesidades)
                $request->validate([
                    'descripcionlinea' => 'required',
                    'cuentaventas' => '',
                    'cuentacompras' => '',
                    'costoventas' => '',
                    'mercaderia' => '',
                    'existencia' => '',
                ]);

                // Buscar el registro por su ID
                $linea = Linea::find($id);

                // Verificar si el registro existe
                if (!$linea) {
                    return response()->json(['error' => 'Registro no encontrado'], 404);
                }

                // Actualizar los datos
                $linea->Descripcion = $request->input('descripcionlinea');
                $linea->CtaVentas = $request->input('cuentaventas');
                $linea->CtaCompras = $request->input('cuentacompras');            
                $linea->CtaMercaderias = $request->input('mercaderia');
                $linea->CostoVentas = $request->input('costoventas');
                $linea->CtaExistencias = $request->input('existencia');

                // Guardar en la base de datos
                $linea->save();
                $id = $linea->id;

                return response()->json(['success' => true, 'id' => $id]);
            } catch (\Exception $e) {
                dd($e->getMessage(), $e->getTrace());
            return response()->json(['error' => $e->getMessage(), 'trace' => $e->getTrace()], 500);   
            }
        }

    public function obtenerDatosinputslineas($id)
    {
        $datos = Linea::find($id);
        return response()->json($datos);
    }

    public function listarDatossublinea($id)
    {
        $linea = Sublinea::select(['id', 'descripcion', 'cuentaVentas', 'cuentaCompras', 'cuentaMerca', 'costVenta'])
        ->where('codigoLinea', $id)
        ->get();
        return response()->json($linea);
    }

    public function guardarsubLinea(Request $request)
    {
        try {
            $request->validate([
                'cbolinea' => 'required',
                'descripcionsublinea' => 'required',
                'sublineacuentaventas' => '',
                'sublineacuentacompras' => '',
                'sublineacostoventas' => '',
                'sublineamercaderia' => '',
            ]);
            $sublineas = new Sublinea;
            $sublineas->codigoLinea = $request->input('cbolinea');
            $sublineas->descripcion = $request->input('descripcionsublinea');
            $sublineas->cuentaVentas = $request->input('sublineacuentaventas');            
            $sublineas->cuentaCompras = $request->input('sublineacuentacompras');
            $sublineas->cuentaMerca = $request->input('sublineacostoventas');
            $sublineas->costventa = $request->input('sublineamercaderia');

            // Guardar en la base de datos
            $sublineas->save();
            $id = $sublineas->id;

            return response()->json(['success' => true, 'id' => $id], 200);
        } catch (\Exception $e) {
            \Log::error('Error: ' . $e->getMessage());
            \Log::error('Request data: ' . json_encode($request->all())); // Add this line    
            // You might want to return a different status code based on the error type
            return response()->json(['success' => false, 'errors' => $request->errors()->toArray()], 500);
        }
    }

    public function modificarsubLinea(Request $request, $id)
    {
        try {
            // Validación de datos (puedes personalizarla según tus necesidades)
            $request->validate([
                'cbolinea' => 'required',
                'descripcionsublinea' => 'required',
                'sublineacuentaventas' => '',
                'sublineacuentacompras' => '',
                'sublineacostoventas' => '',
                'sublineamercaderia' => '',
            ]);

            // Buscar el registro por su ID
            $sublineas = Sublinea::find($id);

            // Verificar si el registro existe
            if (!$sublineas) {
                return response()->json(['error' => 'Registro no encontrado'], 404);
            }

            // Actualizar los datos
            $sublineas->codigoLinea = $request->input('cbolinea');
            $sublineas->descripcion = $request->input('descripcionsublinea');
            $sublineas->cuentaVentas = $request->input('sublineacuentaventas');            
            $sublineas->cuentaCompras = $request->input('sublineacuentacompras');
            $sublineas->cuentaMerca = $request->input('sublineacostoventas');
            $sublineas->costventa = $request->input('sublineamercaderia');

            // Guardar en la base de datos
            $sublineas->save();
            $id = $sublineas->id;

            return response()->json(['success' => true, 'id' => $id]);
        } catch (\Exception $e) {
            dd($e->getMessage(), $e->getTrace());
        return response()->json(['error' => $e->getMessage(), 'trace' => $e->getTrace()], 500);   
        }
    }

    public function obtenerDatosinputssublineas($id)
    {
        $datos = Sublinea::find($id);
        return response()->json($datos);
    }
}