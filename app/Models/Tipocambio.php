<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Tipocambio extends Model
{
 

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

     protected $table = 'tipocambio';

     protected $fillable = [
        'id',   
        'fecha'
        
    ];
}