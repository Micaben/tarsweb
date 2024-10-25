<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
 

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

     protected $table = 'color';

     protected $fillable = [
        'id',   
        'descripcion'
        
    ];
}