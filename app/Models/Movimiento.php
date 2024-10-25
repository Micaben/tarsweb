<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Movimiento extends Model
{
 

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

     protected $table = 'almacenmov';

     protected $fillable = [
        'id', 
        'tipo',  
        'descripcion'
        
    ];
}