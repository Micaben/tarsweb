<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Cabmovpro extends Model
{
 

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

     protected $table = 'cabmovpro';

     protected $fillable = [
        'id',   
        'td'
        
    ];
}