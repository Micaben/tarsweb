<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
class EventoController extends Controller
{
 
    public function guardarEvento(Request $request)
    {
        try {
            // Validación de datos (puedes personalizarla según tus necesidades)
            $request->validate([
                'title' => 'required',
                'clickedDate' => 'required',
                'time' => 'required',
            ]);
            $nombreUsuario = Auth::user()->name;
            $eventos = new Evento;
            $eventos->descripcion = $request->input('title');
            $eventos->fin = $request->input('clickedDate');
            $eventos->hora = $request->input('time');
            $eventos->usercrea = $nombreUsuario;
            // Guardar en la base de datos
            $eventos->save();
            $id = $eventos->id;

            return response()->json(['success' => true, 'id' => $id]);
        } catch (\Exception $e) {
            \Log::error('Error durante la creación del usuario: ' . $e->getMessage());
            \Log::error('Request data: ' . json_encode($request->all())); // Añadir esta línea
            return response()->json(['success' => false, 'errors' => $request->errors()->toArray()]);
        }
    }

    public function modificarEvento(Request $request, $id)
    {
        try {
            // Validación de datos (puedes personalizarla según tus necesidades)
            $request->validate([
                'title' => 'required',                
                'time' => 'required',              
                
            ]);

            // Buscar el registro por su ID
            $eventos = Evento::find($id);

            // Verificar si el registro existe
            if (!$eventos) {
                return response()->json(['error' => 'Registro no encontrado'], 404);
            }

            // Actualizar los datos
            $nombreUsuario = Auth::user()->name;            
            $eventos->descripcion = $request->input('title');
            $eventos->hora = $request->input('time');
            $eventos->usercrea = $nombreUsuario;
            // Guardar en la base de datos
            $eventos->save();
            $id = $eventos->id;

            return response()->json(['success' => true, 'id' => $id]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function descartarEvento(Request $request, $id)
    {
        try {
            // Buscar el evento por su ID
            $evento = Evento::find($id);
    
            // Verificar si el evento existe
            if (!$evento) {
                return response()->json(['error' => 'Evento no encontrado'], 404);
            }
    
            // Actualizar el campo "descartado" a 1 (indicando que está descartado)
            $evento->descartado = 1;
            $evento->save();
    
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function obtenerEventos(Request $request)
    {
        $mes = $request->query('mes');
        $anio = $request->query('anio');
        $datosTipoCambio = Evento::whereYear('fin', $anio)
            ->whereMonth('fin', $mes)
            ->selectRaw('id, fin, descripcion, hora, descartado')
        ->get();     
        return response()->json($datosTipoCambio);
    }

    public function verificarEventoDescartado()
    {
        try {
            // Obtener todos los eventos que no han sido descartados
            $eventos = Evento::whereNull('descartado')->orWhere('descartado', 0)->get();
    
            // Retornar la lista de eventos no descartados
            return response()->json(['eventos' => $eventos]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}