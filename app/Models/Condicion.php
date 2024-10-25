<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Condicion extends Model
{
 

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

     protected $table = 'condicionventa';

     protected $fillable = [
        'id',   
        'descripcion'
        
    ];
}