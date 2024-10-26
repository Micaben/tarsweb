<?php

namespace App\Http\Controllers;

use App\Models\Cargo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
class CargoController extends Controller
{
    public static function obtenerCargo()
    {
        $lineas = Cargo::all(); // Asegúrate de ajustar según tu modelo y tabla

        return response()->json($lineas);
    }

}