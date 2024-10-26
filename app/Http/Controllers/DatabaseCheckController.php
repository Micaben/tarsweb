<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DatabaseCheckController extends Controller
{
    //
    public function checkDatabaseConnection()
    {
        try {
            DB::connection()->getPdo();
            return "Conexión exitosa";
        } catch (\Exception $e) {
            return "Error de conexión: " . $e->getMessage();
        }
    }
}
