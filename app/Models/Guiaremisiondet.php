<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Guiaremisiondet extends Model
{
 

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

     protected $table = 'guiaremisiond';

     protected $fillable = [
        'id',   
        'guiaremisioncab_id',
    ];

    public function guiaRemision()
    {
        return $this->belongsTo(Guiaremisioncab::class, 'guiaremisioncab_id', 'id');
    }
}