<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class Transportista extends Model
{
 

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

     protected $table = 'transporteempresas';

     protected $fillable = [
        'id',   
        'ruc',
        'empresa'
        
    ];

    public function transportista($id)
    {
        return $this->select(['transportista.id', 'transportista.nombres', 'transportista.apellidos', 'transportista.dni', 'transportista.activo'])
        ->join('transportista', 'transporteempresas.ruc', '=', 'transportista.empresa') // Ajusta las claves foráneas según tu esquema de base de datos
        ->where('transporteempresas.ruc', $id)
        ->get();
    }

    

    public function transportistainputs($id)
    {
        return $this->from('transportista')
        ->select(['id', 'nombres', 'apellidos', 'dni', 'licencia', 'unidad', 'placa', 'activo', 'empresa'])
        ->where('id', $id)
        ->get();
    }


}