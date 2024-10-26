<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Identpedidos extends Model
{
 

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

     protected $table = 'identpedidos';

     protected $fillable = [
        'id',  
        
    ];
}