<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
 

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

     protected $table = 'productos';
     protected $codproducto;
     protected $descripcion;
     protected $idumedida;
     protected $fechavencimiento;
     protected $saldoactual;
     protected $diasrestantes;
     protected $fillable = [
        'id',
        'codproducto',        
        'descripcion','idumedida', 'fechavencimiento', 'saldoactual', 'diasrestantes'
        
    ];

    public function setProductos($productos)
    {
        $this->productos = $productos;
    }

    public function setTotalProductos($productos)
    {
        $this->productos = $productos;
    }



    // Métodos Setter
    public function setCod($codproducto) {
        $this->codproducto = $codproducto;
    }

    public function setDesc($descripcion) {
        $this->descripcion = $descripcion;
    }

    public function setIdum($idumedida) {
        $this->idumedida = $idumedida;
    }

    public function setFechav($fechavencimiento) {
        $this->fechavencimiento = $fechavencimiento;
    }

    public function setCanti($saldoactual) {
        $this->saldoactual = $saldoactual;
    }

    public function setDiasRestantes($diasrestantes) {
        $this->diasrestantes = $diasrestantes;
    }
}