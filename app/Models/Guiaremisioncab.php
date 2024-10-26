<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Guiaremisioncab extends Model
{
 

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

     protected $table = 'guiaremision';

     protected $fillable = [
        'id',   
        
    ];

    public function detalles()
    {
        return $this->hasMany(Guiaremisiondet::class, 'guiaremisioncab_id', 'id'); 

    }
    
}