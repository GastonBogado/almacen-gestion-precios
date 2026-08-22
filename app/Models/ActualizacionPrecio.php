<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActualizacionPrecio extends Model
{
    protected $table = 'actualizacion_precio';

    protected $fillable = [
        'producto_id',
        'proveedor_id',
        'precio_anterior',
        'precio_nuevo',
        'telegram_user_id',
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
