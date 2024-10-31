<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Comprobantes_Allowance extends Model
{
 

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

     protected $table = 'comprobantes_allowancecharge';
  
     protected $fillable = [
        'Id',    
    ];


}