<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Pedidosdet extends Model
{
 

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

     protected $table = 'notapedidodetalle';
  
     protected $fillable = [
        'id',    
    ];

    public function cotizacion()
    {
        return $this->belongsTo(PedidosCab::class, 'id');
    }
}