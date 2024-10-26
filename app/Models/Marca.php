<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Marca extends Model
{
 

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

     protected $table = 'marcas';

     protected $fillable = [
        'id',   
        'descripcion'
        
    ];
}