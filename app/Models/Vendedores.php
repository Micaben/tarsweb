<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Vendedores extends Model
{
 

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

     protected $table = 'vendedores';
  
     protected $fillable = [
        'id',                
        'nombres',
    ];

   
}