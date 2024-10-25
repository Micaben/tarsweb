<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Comprobantes_paymentterms extends Model
{
 

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

     protected $table = 'comprobantes_paymentterms';
  
     protected $fillable = [
        'Id',    
    ];


}