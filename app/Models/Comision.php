<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Comision extends Model
{
 

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

     protected $table = 'comision';

     protected $fillable = [
        'id',   
        'descripcion'
        
    ];
}