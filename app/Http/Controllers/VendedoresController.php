<?php

namespace App\Http\Controllers;

use App\Models\Vendedores;
use App\Models\Comision;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
class VendedoresController extends Controller
{

    public function mostrarMantenimiento()
    {
        return view('vendedores');
    }

    public function listarVendedor()
    {
        $mot = Vendedores::select(['id', 'nombres', 'telefono','activo'])->get();
        return response()->json($mot);
    }

    public function guardarVendedor(Request $request)
    {
        try {
            $request->validate([
                'nombres' => 'required',
                'dni' => '',
                'direccion' => '',
                'telefono' => '',
                'email' => '',                
            ]);

            $vendedor = new Vendedores;
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

    public function modificarVendedor(Request $request, $id)
    {
        try {
            // Validación de datos (puedes personalizarla según tus necesidades)
            $request->validate([
                'nombres' => 'required',
                'dni' => '',
                'direccion' => '',
                'telefono' => '',
                'email' => '',           
            ]);

            $vendedor = Vendedores::find($id);
            // Verificar si el registro existe
            if (!$vendedor) {
                return response()->json(['error' => 'Registro no encontrado'], 404);
            }

            // Actualizar los datos
            $vendedor->nombres = $request->input('nombres');
            $vendedor->dni = $request->input('dni');
            $vendedor->direccion = $request->input('direccion');
            $vendedor->telefono = $request->input('telefono');
            $vendedor->email = $request->input('email');
            $vendedor->activo = $request->has('estado');    
            $vendedor->save();
            $id = $vendedor->id;

            return response()->json(['success' => true, 'id' => $id]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function obtenerDatosinputsvendedor($id)
    {
        $datos = Vendedores::find($id);
        return response()->json($datos);
    }

    public function listarComision()
    {
        $mot = Comision::select(['id', 'descripcion', 'porcent'])->get();
        return response()->json($mot);
    }

    public function guardarComision(Request $request)
    {
        try {
            $request->validate([
                'descripcioncomision' => 'required',
                'porcentajecomision' => '',                
            ]);

            $comision = new Comision;
            $comision->descripcion = $request->input('descripcioncomision');
            $comision->porcent = $request->input('porcentajecomision'); 
            // Guardar en la base de datos
            
                $comision->save();
                $id = $comision->id;

                return response()->json(['success' => true, 'id' => $id]);
                          
        } catch (\Exception $e) {
            \Log::error('Error durante la creación del usuario: ' . $e->getMessage());
            \Log::error('Request data: ' . json_encode($request->all())); // Añadir esta línea
            return response()->json(['success' => false, 'errors' => $request->errors()->toArray()]);
        }
    }

    public function modificarComision(Request $request, $id)
    {
        try {
            // Validación de datos (puedes personalizarla según tus necesidades)
            $request->validate([
                'descripcioncomision' => 'required',
                'porcentajecomision' => '',      
            ]);

            $comision = Comision::find($id);
            // Verificar si el registro existe
            if (!$comision) {
                return response()->json(['error' => 'Registro no encontrado'], 404);
            }

            // Actualizar los datos
            $comision->descripcion = $request->input('descripcioncomision');
            $comision->porcent = $request->input('porcentajecomision');  
            $comision->save();
            $id = $comision->id;

            return response()->json(['success' => true, 'id' => $id]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function obtenerDatosinputscomision($id)
    {
        $datos = Comision::find($id);
        return response()->json($datos);
    }
}