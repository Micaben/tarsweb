<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Cotizacioncab extends Model
{
 

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

     protected $table = 'cotizacioncab';
  
     protected $fillable = [
        'Id',    
    ];

    public function detalles()
    {
        return $this->hasMany(CotizacionDet::class, 'id'); // Asegúrate de que 'cotizacion_id' sea la clave foránea
    }
}