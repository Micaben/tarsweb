<?php

namespace App\Http\Controllers;

use App\Models\Proveedor;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ProveedorController extends Controller
{

    public function mostrarMantenimiento()
    {
        return view('proveedor');
    }

    public function obtenerdatosproveedor()
    {
        $proveedor = Proveedor::all(); // Asegúrate de ajustar según tu modelo y tabla

        return response()->json($proveedor);
    }

    public function listarDatosProveedor()
    {
        $proveedor = Proveedor::select(['id','proveedor', 'nombres','direccion', 'telefono'])->get();
        return response()->json($proveedor);
    }

    public function guardarProveedor(Request $request)
    {
        try {
            // Validación de datos (puedes personalizarla según tus necesidades)
            $request->validate([
                'numero' => 'required',
                'razonsocial' => 'required',
                'direccion' => 'required',
                'ubigeo' => '',
                'cbotipodocumento' => '',
                'cbotipopersona' => '',
                'correo' => '',
                'telefono' => '',                
                'contacto' => '',
                'cargocontacto' => '',
                'pais' => '',
            ]);
            $proveedor = new Proveedor;
            $proveedor->proveedor = $request->input('numero');
            $proveedor->nombres = $request->input('razonsocial');
            $proveedor->direccion = $request->input('direccion');
            $proveedor->tipod = $request->input('cbotipodocumento');
            $proveedor->tipop = $request->input('cbotipopersona');
            $proveedor->correo = $request->input('correo');
            $proveedor->telefono = $request->input('telefono');
            $proveedor->contacto = $request->input('contacto');
            $proveedor->cargocontacto = $request->input('cargocontacto');
            $proveedor->estado = $request->has('estado');
            $proveedor->pais = $request->input('pais');
            // Guardar en la base de datos
            $proveedor->save();
            $id = $proveedor->id;

            return response()->json(['success' => true, 'id' => $id]);
        } catch (\Exception $e) {
            \Log::error('Error durante la creación del usuario: ' . $e->getMessage());
            \Log::error('Request data: ' . json_encode($request->all())); // Añadir esta línea
            return response()->json(['success' => false, 'errors' => $request->errors()->toArray()]);
        }
    }

    public function modificarProveedor(Request $request, $id)
    {
        try {
            // Validación de datos (puedes personalizarla según tus necesidades)
            $request->validate([
                'numero' => 'required',
                'razonsocial' => 'required',
                'direccion' => 'required',
                'telefono' => '',
                'correo' => '',                               
                'contacto' => '',
                'pais' => '', 
               
                
            ]);

            // Buscar el registro por su ID
            $proveedor = Proveedor::find($id);

            // Verificar si el registro existe
            if (!$proveedor) {
                return response()->json(['error' => 'Registro no encontrado'], 404);
            }

            // Actualizar los datos
            $proveedor->proveedor = $request->input('numero');
            $proveedor->nombres = $request->input('razonsocial');
            $proveedor->direccion = $request->input('direccion');           
            $proveedor->telefono = $request->input('telefono');
            $proveedor->correo = $request->input('correo');            
            $proveedor->contacto = $request->input('contacto');
            $proveedor->pais = $request->input('pais');  
            // Guardar en la base de datos
            $proveedor->save();
            $id = $proveedor->id;

            return response()->json(['success' => true, 'id' => $id]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function obtenerDatosinputsproveedor($id)
    {
        $datos = Proveedor::find($id);
        return response()->json($datos);
    }

    

}