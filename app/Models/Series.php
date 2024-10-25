<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Series extends Model
{
 

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

     protected $table = 'seriedoc';

     protected $fillable = [
        'id',   
        'serie'
        
    ];
}