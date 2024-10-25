<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Comprobantesdet extends Model
{
 

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

     protected $table = 'comprobantesd';
  
     protected $fillable = [
        'Id',    
    ];

    public function detalles()
    {
        return $this->hasMany(ComprobantesDet::class, 'id'); // Asegúrate de que 'cotizacion_id' sea la clave foránea
    }
}