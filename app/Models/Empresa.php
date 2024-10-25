<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    use HasFactory;

    protected $fillable = ['empresa', 'direccion', 'telefono']; // Atributos de la empresa

    // Ejemplo de relación con el modelo User
    public function usuarios()
    {
        return $this->hasMany(User::class);
    }

}
