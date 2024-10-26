<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Sublinea extends Model
{
 

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

     protected $table = 'sublinea';
  
     protected $fillable = [
        'id',                
        'descripcion',
    ];

   
}