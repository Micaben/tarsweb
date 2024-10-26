<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Evento extends Model
{
 

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

     protected $table = 'eventos';

     protected $fillable = [
        'id',   
        'descripcion'
        
    ];
}