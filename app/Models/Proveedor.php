<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    protected $table = 'proveedores';
    protected $fillable = ['nombre'];

    public function productoProveedores()
    {
        return $this->hasMany(ProductoProveedor::class);
    }

    public function ofertas()
    {
        return $this->hasMany(Oferta::class);
    }
}
