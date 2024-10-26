<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Movalmacencab extends Model
{
 

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

     protected $table = 'Movalmacencab';

     protected $fillable = [
        'id',                
        'serie',
        
    ];
}