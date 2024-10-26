<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class cotizaciondet extends Model
{
 

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

     protected $table = 'cotizaciondet';
  
     protected $fillable = [
        'id',    
    ];

    public function cotizacion()
    {
        return $this->belongsTo(CotizacionCab::class, 'id');
    }
}