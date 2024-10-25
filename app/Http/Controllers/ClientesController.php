<?php

namespace App\Http\Controllers;

use App\Models\Clientes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;


class ClientesController extends Controller
{
    public function mostrarMantenimiento()
    {
        return view('clientes');
    }

    public function listarDatosClientes()
    {
        $medidas = Clientes::select(['cliente.id', 'cliente.ruc', 'cliente.razonsocial', 'cliente.direccion', 'cliente.activo', 'cliente.tipod','cliente.ubigeo', 'cliente.moneda', 'cliente.condicion', 'cliente.agretencion', 'cliente.vendedor','tipomoneda.mda_codigo as mda_codigo','condicionventa.plazo as plazo'])
        ->join('tipomoneda', 'cliente.moneda', '=', 'tipomoneda.id')
        ->join('condicionventa', 'cliente.condicion', '=', 'condicionventa.id')
        ->get();
        return response()->json($medidas);
    }

    public function guardarClientes(Request $request)
    {
        try {
            $request->validate([
                'numeroruc' => 'required',
                'razonsocial' => 'required',
                'nombrecomercial' => '',
                'direccion' => 'required',
                'ubigeo' => 'required',
                'cbotipodocumento' => '',
                'cbotipopersona' => '',
                'correo' => '',
                'telefono' => '',
                'cbovendedor' => '',
                'cbocondicion' => '',
                'cbomoneda' => '',
                'nombrecontacto' => '',
                'cargocontacto' => '',
                'usuariocrea' => '',

            ]);
            $nombreUsuario = Auth::user()->name;
            $clientes = new Clientes;
            $clientes->ruc = $request->input('numeroruc');
            $clientes->razonsocial = $request->input('razonsocial');
            $clientes->nomcomercial = $request->input('nombrecomercial');
            $clientes->direccion = $request->input('direccion');
            $clientes->ubigeo = $request->input('ubigeo');
            $clientes->tipod = $request->input('cbotipodocumento');
            $clientes->tipop = $request->input('cbotipopersona');
            $clientes->correo = $request->input('correo');
            $clientes->telefono = $request->input('telefono');
            $clientes->vendedor = $request->input('cbovendedor');
            $clientes->condicion = $request->input('cbocondicion');
            $clientes->moneda = $request->input('cbomoneda');
            $clientes->nomcontacto = $request->input('cbocondicion');
            $clientes->cargocontacto = $request->input('cbomoneda');
            $clientes->nomcontacto = $request->input('nombrecontacto');
            $clientes->cargocontacto = $request->input('cargocontacto');
            $clientes->usuariocrea = $nombreUsuario;
            $clientes->activo = $request->has('estado');
            $clientes->agretencion = $request->has('agretencion');
            // Guardar en la base de datos

            $clientes->save();
            $id = $clientes->id;

            return response()->json(['success' => true, 'id' => $id]);

        } catch (\Exception $e) {
            \Log::error('Error durante la creación del cliente: ' . $e->getMessage());
            \Log::error('Request data: ' . json_encode($request->all())); // Añadir esta línea
            return response()->json(['success' => false, 'errors' => $request->errors()->toArray()]);
        }
    }

    public function modificarClientes(Request $request, $id)
    {
        try {
            $request->validate([
                'numeroruc' => 'required',
                'razonsocial' => 'required',
                'nombrecomercial' => '',
                'direccion' => 'required',
                'ubigeo' => 'required',
                'cbotipodocumento' => '',
                'cbotipopersona' => '',
                'correo' => '',
                'telefono' => '',
                'cbovendedor' => '',
                'cbocondicion' => '',
                'cbomoneda' => '',
                'nombrecontacto' => '',
                'cargocontacto' => '',
                'usuariocrea' => '',
            ]);
            $nombreUsuario = Auth::user()->name;
            $clientes = Clientes::find($id);
            // Verificar si el registro existe
            if (!$clientes) {
                return response()->json(['error' => 'Registro no encontrado'], 404);
            }

            // Actualizar los datos
            $clientes->ruc = $request->input('numeroruc');
            $clientes->razonsocial = $request->input('razonsocial');
            $clientes->nomcomercial = $request->input('nombrecomercial');
            $clientes->direccion = $request->input('direccion');
            $clientes->ubigeo = $request->input('ubigeo');
            $clientes->tipod = $request->input('cbotipodocumento');
            $clientes->tipop = $request->input('cbotipopersona');
            $clientes->correo = $request->input('correo');
            $clientes->telefono = $request->input('telefono');
            $clientes->vendedor = $request->input('cbovendedor');
            $clientes->condicion = $request->input('cbocondicion');
            $clientes->moneda = $request->input('cbomoneda');
            $clientes->nomcontacto = $request->input('nombrecontacto');
            $clientes->cargocontacto = $request->input('cargocontacto');
            $clientes->usuariomodif = $nombreUsuario;
            $clientes->activo = $request->has('estado');
            $clientes->agretencion = $request->has('agretencion');
            $clientes->save();
            $id = $clientes->id;

            return response()->json(['success' => true, 'id' => $id]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function obtenerDatosinputsclientes($ruc)
    {
        $datos = Clientes::find($ruc);
        return response()->json($datos);
    }

    public static function obtenerTipop()
    {
        return DB::select("SELECT cod, deascripcion FROM tablavarios WHERE clase = 'TP'");
    }

    public static function obtenerTipod()
    {
        return DB::select("SELECT cod, deascripcion FROM tablavarios WHERE clase = 'TD'");
    }

    public static function obtenerMoneda()
    {
        return DB::select("SELECT cod, deascripcion FROM tablavarios WHERE clase = 'MON'");
    }

    public static function obtenerDepartamento()
    {
        return DB::select("SELECT DISTINCT Ubi_Departamento FROM ubigeoe  order by Ubi_Departamento asc");
    }

    public static function obtenerProvincia(Request $request)
    {
        $departamento = $request->input('departamento');
        $provincias = DB::select("SELECT DISTINCT ubi_Provincia FROM ubigeoe WHERE Ubi_Departamento = ? ORDER BY ubi_Provincia ASC", [$departamento]);
        return response()->json($provincias);
    }

    public static function obtenerDistrito(Request $request)
    {
        $provincias = $request->input('provincia');
        $distrtito = DB::select("SELECT DISTINCT Ubi_Distrito FROM ubigeoe WHERE ubi_Provincia = ? ORDER BY Ubi_Distrito ASC", [$provincias]);
        return response()->json($distrtito);
    }

    public static function obtenerUbigeo(Request $request)
    {
        $distrito = $request->input('provincia');
        $ubigeo = DB::select("SELECT DISTINCT Ubi_Codigo FROM ubigeoe WHERE Ubi_Distrito = ? ", [$distrito]);
        return response()->json($ubigeo);
    }

    public function buscarRuc(Request $request)
    {
        $ruc = $request->input('ruc');
        $apiUrl = "https://dniruc.apisperu.com/api/v1/ruc/{$ruc}?token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJlbWFpbCI6Im1pY2hhZWxzOTJAaG90bWFpbC5jb20ifQ.qmH_7uL5kIlwlimHRh4-1YD6E7oi2V6SrUIvdrRzBcY";

        try {
            $response = Http::get($apiUrl);

            if ($response->status() === 200) {
                return response()->json($response->json());
            } else {
                return response()->json(['error' => 'Error en la solicitud. Código de estado: ' . $response->status()], 500);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error en la solicitud: ' . $e->getMessage()], 500);
        }
    }

    public function buscarDNI(Request $request)
    {
        $dni = $request->input('dni');
        $apiUrl = "https://dniruc.apisperu.com/api/v1/dni/{$dni}?token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJlbWFpbCI6Im1pY2hhZWxzOTJAaG90bWFpbC5jb20ifQ.qmH_7uL5kIlwlimHRh4-1YD6E7oi2V6SrUIvdrRzBcY";

        try {
            $response = Http::get($apiUrl);

            if ($response->status() === 200) {
                return response()->json($response->json());
            } else {
                return response()->json(['error' => 'Error en la solicitud. Código de estado: ' . $response->status()], 500);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error en la solicitud: ' . $e->getMessage()], 500);
        }
    }

    public function obtenerDatosinputsclientesRUC($ruc)
    {
        $datos = Clientes::where('ruc', $ruc)->first();
        if ($datos) {
            return response()->json($datos);
        } else {
            \Log::info('No se encontraron datos para el RUC: ' . $ruc);
        }    
    }
}
