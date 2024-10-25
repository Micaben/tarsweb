<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Umedida extends Model
{
 

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

     protected $table = 'unidadmed';

     protected $fillable = [
        'id',                
        'umedida',
        'descripcion'
    ];

   
}