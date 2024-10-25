<?php

namespace App\Http\Controllers;

use App\Models\Naturaleza;

class NaturalezaController extends Controller
{

    public function mostrarMantenimiento()
    {
        return view('naturaleza');
    }

    public function obtenerNaturaleza()
    {
        $naturalezas = Naturaleza::all(); // Asegúrate de ajustar según tu modelo y tabla

        return response()->json($naturalezas);
    }

    public function listarDatosNaturaleza()
    {
        $naturalezas = Naturaleza::select(['codCategoria', 'descripcion', 'codSunat'])->get();
        return response()->json($naturalezas);
    }
}