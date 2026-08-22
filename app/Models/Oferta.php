<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Oferta extends Model
{
     protected $fillable = [
        'producto_id',
        'proveedor_id',
        'precio_oferta',
        'presentacion',
        'vigente_desde',
        'vigente_hasta',
        'notas',
    ];

    protected $casts = [
        'vigente_desde' => 'date',
        'vigente_hasta' => 'date',
    ];

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class);
    }
}
