<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Comprobantes_tributos extends Model
{
 

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

     protected $table = 'Comprobantes_tributos';

     protected $fillable = [
        'id',   
        
    ];
}