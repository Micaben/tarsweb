<?php

namespace App\Http\Controllers;

use App\Models\Almacen;
use App\Models\Transportista;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
class AlmacenController extends Controller
{
    public function mostrarMantenimiento()
    {
        return view('almacen');
    }

    public function listarDatosAlmacen()
    {
        $medidas = Almacen::select(['id', 'descripcion', 'direccion','estado'])->get();
        return response()->json($medidas);
    }

    public function guardarAlmacen(Request $request)
    {
        try {
            $request->validate([
                'nombre' => 'required',
                'direccion' => 'required',
                'telefono' => '',
                'contacto' => '',
                'correo' => '',

            ]);

            $almacen = new Almacen;
            $almacen->descripcion = $request->input('nombre');
            $almacen->direccion = $request->input('direccion');
            $almacen->telefono = $request->input('telefono');
            $almacen->contacto = $request->input('contacto');
            $almacen->correo = $request->input('correo');
            $almacen->estado = $request->has('estado');
            $almacen->contable = $request->has('contable');

            // Guardar en la base de datos
            
                $almacen->save();
                $id = $almacen->id;

                return response()->json(['success' => true, 'id' => $id]);
                          
        } catch (\Exception $e) {
            \Log::error('Error durante la creación del usuario: ' . $e->getMessage());
            \Log::error('Request data: ' . json_encode($request->all())); // Añadir esta línea
            return response()->json(['success' => false, 'errors' => $request->errors()->toArray()]);
        }
    }

    public function modificarAlmacen(Request $request, $id)
    {
        try {
            // Validación de datos (puedes personalizarla según tus necesidades)
            $request->validate([
                'nombre' => 'required',
                'direccion' => 'required',
                'telefono' => '',
                'contacto' => '',
                'correo' => '',
            ]);

            $almacen = Almacen::find($id);
            // Verificar si el registro existe
            if (!$almacen) {
                return response()->json(['error' => 'Registro no encontrado'], 404);
            }

            // Actualizar los datos
            $almacen->descripcion = $request->input('nombre');
            $almacen->direccion = $request->input('direccion');
            $almacen->telefono = $request->input('telefono');
            $almacen->contacto = $request->input('contacto');
            $almacen->correo = $request->input('correo');
            $almacen->estado = $request->has('estado');
            $almacen->contable = $request->has('contable');
            $almacen->save();
            $id = $almacen->id;

            return response()->json(['success' => true, 'id' => $id]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function obtenerDatosinputsalmacen($id)
    {
        $datos = Almacen::find($id);
        return response()->json($datos);
    }

    public function mostrarMantenimientoTransportista()
    {
        return view('transportista');
    }

    public function listarDatosFlota()
    {
        $medidas = Transportista::select(['id', 'ruc', 'empresa'])->get();
        return response()->json($medidas);
    }

    public static function obtenerEmpresat()
    {
        return DB::select("SELECT ruc, empresa FROM transporteempresas");
    }
    
    public function listarDatosTransportista($id)
    {
        $transportistaData = (new Transportista())->transportista($id);

    return response()->json($transportistaData);
    }

    public function obtenerDatosinputsempresat($id)
    {
        $datos = Transportista::find($id);
        return response()->json($datos);
    }

    public function listarDatosEmpresat($id)
    {
        $transportistaData = (new Transportista())->transportista($id);

    return response()->json($transportistaData);
    }

    public function obtenerDatosinputstransportista($id)
    {
        $transportistaData = (new Transportista())->transportistainputs($id);

        return response()->json($transportistaData);
    }

    public function guardarEmpresaTransporte(Request $request)
    {
        try {
            $request->validate([
                'rucempresat' => 'required',
                'razonsocial' => 'required',

            ]);

            $transporte = new Transportista;
            $transporte->ruc = $request->input('rucempresat');
            $transporte->empresa = $request->input('razonsocial');

            // Guardar en la base de datos
            
                $transporte->save();
                $id = $transporte->id;

                return response()->json(['success' => true, 'id' => $id]);
                          
        } catch (\Exception $e) {
            \Log::error('Error durante la creación del usuario: ' . $e->getMessage());
            \Log::error('Request data: ' . json_encode($request->all())); // Añadir esta línea
            return response()->json(['success' => false, 'errors' => $request->errors()->toArray()]);
        }
    }

    public function modificarEmpresaTransporte(Request $request, $id)
    {
        try {
            // Validación de datos (puedes personalizarla según tus necesidades)
            $request->validate([
                'rucempresat' => 'required',
                'razonsocial' => 'required',
            ]);

            $transporte = Transportista::find($id);
            // Verificar si el registro existe
            if (!$transporte) {
                return response()->json(['error' => 'Registro no encontrado'], 404);
            }

            // Actualizar los datos
            $transporte->ruc = $request->input('rucempresat');
            $transporte->empresa = $request->input('razonsocial');
            $transporte->save();
            $id = $transporte->id;

            return response()->json(['success' => true, 'id' => $id]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function guardarTransportista(Request $request)
    {
        try {
            $request->validate([
                'nombre' => 'required',
                'apellido' => 'required',
                'dni' => '',
                'licencia' => '',
                'unidad' => '',
                'placa' => '',
                'cboempresa' => '',
            ]);
    
            // Construir el arreglo de datos
            $datosTransportista = [
                'nombres' => $request->input('nombre'),
                'apellidos' => $request->input('apellido'),
                'dni' => $request->input('dni'),
                'licencia' => $request->input('licencia'),
                'unidad' => $request->input('unidad'),
                'placa' => $request->input('placa'),
                'activo' => $request->has('estado'),
                'empresa' => $request->input('cboempresa'),
            ];
    
            // Insertar el registro usando la clase DB
            $idInsertado = DB::table('transportista')->insertGetId($datosTransportista);
    
            return response()->json(['success' => true, 'id' => $idInsertado]);
        } catch (\Exception $e) {
            \Log::error('Error durante la creación del usuario: ' . $e->getMessage());
            \Log::error('Request data: ' . json_encode($request->all())); // Añadir esta línea
            return response()->json(['success' => false, 'errors' => $request->errors()->toArray()]);
        }
    }

    public function modificaTransportista(Request $request, $id)
    {
        try {
            // Validación de datos (puedes personalizarla según tus necesidades)
            $request->validate([
                'nombre' => 'required',
                'apellido' => 'required',
                'dni' => '',
                'licencia' => '',
                'unidad' => '',
                'placa' => '',
                'cboempresa' => '',
            ]);
    
            // Construir el arreglo de datos
            $datosTransportista = [
                'nombres' => $request->input('nombre'),
                'apellidos' => $request->input('apellido'),
                'dni' => $request->input('dni'),
                'licencia' => $request->input('licencia'),
                'unidad' => $request->input('unidad'),
                'placa' => $request->input('placa'),
                'activo' => $request->has('estado'),
                'empresa' => $request->input('cboempresa'),
            ];
    
            // Actualizar el registro usando la clase DB
            DB::table('transportista')
                ->where('id', $id)
                ->update($datosTransportista);
    
            return response()->json(['success' => true, 'id' => $id]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
