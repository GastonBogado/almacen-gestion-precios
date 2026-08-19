<?php

namespace App\Http\Controllers;

use App\Models\ActualizacionPrecio;
use App\Models\Categoria;
use App\Models\Gasto;

class DashboardController extends Controller
{
    public function index()
    {
         $categorias = Categoria::with('productos')
            ->orderByRaw("nombre = 'Sin categoría' ASC")
            ->orderBy('nombre')
            ->paginate(10, ['*'], 'insumos_page')
            ->withQueryString();

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
            'gastos' => $gastos,
            'actualizaciones' => $actualizaciones,
        ]);
    }
}
