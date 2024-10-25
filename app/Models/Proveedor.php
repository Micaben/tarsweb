<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
 

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

     protected $table = 'proveedor';

     protected $fillable = [
        'id',  
        'proveedor', 
        'nombres'
        
    ];
}