<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gasto extends Model
{
     protected $table = 'gasto';

    protected $fillable = [
        'producto_id',
        'cantidad',
        'monto',
        'telegram_user_id',
    ];

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
}
