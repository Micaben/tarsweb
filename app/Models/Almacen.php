<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Almacen extends Model
{
 

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

     protected $table = 'almacen';

     protected $fillable = [
        'id',   
        'descripcion'
        
    ];
}