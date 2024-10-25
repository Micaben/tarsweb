<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Naturaleza extends Model
{
 

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

     protected $table = 'categoria';

     protected $fillable = [
        'CodCategoria',                
        'Descripcion',
        
    ];
}