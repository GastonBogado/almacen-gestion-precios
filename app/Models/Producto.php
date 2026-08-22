<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $table = 'producto';

    protected $fillable = [
        'nombre',
        'categoria_id',
        'unidad',
        'foto',
        'precio_actual',
        'margen_pct',
        'precio_venta_calculado',
    ];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function actualizacionesPrecio()
    {
        return $this->hasMany(ActualizacionPrecio::class);
    }

    public function gastos()
    {
        return $this->hasMany(Gasto::class);
    }
    public function productoProveedores()
    {
    return $this->hasMany(ProductoProveedor::class);
    }

    public function ofertas()
    {
    return $this->hasMany(Oferta::class);
    }
}
