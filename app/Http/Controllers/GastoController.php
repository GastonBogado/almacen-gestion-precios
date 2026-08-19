<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gasto;
use App\Models\Producto;
use App\Models\Categoria;
class GastoController extends Controller
{
    public function store(Request $request)
    {
        try {
            $request->validate([
                'producto' => 'required|string',
                'cantidad' => 'required|numeric|min:0.01',
                'unidad' => 'required|string',
                'monto' => 'required|numeric|min:0.01',
                'categoria_sugerida' => 'required|string',
                'telegram_user_id' => 'required|string',
            ]);

            // Buscar la categoría por nombre, o usar "Sin categoría" si no existe
            $todasCategorias = Categoria::all();
$mejorCategoria = null;
$mejorPorcentaje = 0;

foreach ($todasCategorias as $cat) {
    similar_text(
        strtolower($request->categoria_sugerida),
        strtolower($cat->nombre),
        $porcentaje
    );

    if ($porcentaje > $mejorPorcentaje) {
        $mejorPorcentaje = $porcentaje;
        $mejorCategoria = $cat;
    }
}

// Solo usamos el match si supera un umbral de confianza razonable (ej: 60%)
if ($mejorPorcentaje >= 60) {
    $categoria = $mejorCategoria;
} else {
    $categoria = Categoria::where('nombre', 'Sin categoría')->first();
}

            $producto = Producto::firstOrNew(['nombre' => $request->producto]);
            $producto->categoria_id = $categoria->id;
            $producto->unidad = $request->unidad;
            $producto->precio_actual = $request->monto / $request->cantidad;
            $producto->margen_pct = $producto->margen_pct ?? 40;
            $producto->precio_venta_calculado = $producto->precio_actual * (1 + $producto->margen_pct / 100);
            $producto->save();

            $gasto = Gasto::create([
                'producto_id' => $producto->id,
                'cantidad' => $request->cantidad,
                'monto' => $request->monto,
                'telegram_user_id' => $request->telegram_user_id,
            ]);

            return response()->json([
                'message' => 'Gasto registrado correctamente',
                'producto' => $producto,
                'gasto' => $gasto,
            ], 200);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
