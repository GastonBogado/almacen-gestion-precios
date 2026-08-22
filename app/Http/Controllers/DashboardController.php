<?php

namespace App\Http\Controllers;

use App\Models\ActualizacionPrecio;
use App\Models\Categoria;
use App\Models\Gasto;
use App\Models\Proveedor;

class DashboardController extends Controller
{
    public function index()
    {
         $categorias = Categoria::with('productos.productoProveedores.proveedor')
            ->orderByRaw("nombre = 'Sin categoría' ASC")
            ->orderBy('nombre')
            ->get();

        $proveedores = Proveedor::orderBy('nombre')->get();

        $gastos = Gasto::with('producto')
            ->latest()
            ->paginate(10, ['*'], 'gastos_page')
            ->withQueryString();

        $actualizaciones = ActualizacionPrecio::with('producto')
            ->latest()
            ->paginate(10, ['*'], 'actualizaciones_page')
            ->withQueryString();

        return view('dashboard', [
            'categorias' => $categorias,
            'proveedores' => $proveedores,
            'gastos' => $gastos,
            'actualizaciones' => $actualizaciones,
        ]);
    }
}
