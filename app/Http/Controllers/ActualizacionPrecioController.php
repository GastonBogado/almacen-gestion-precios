<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Categoria;
use App\Models\ActualizacionPrecio;
use App\Models\Producto;
class ActualizacionPrecioController extends Controller
{
    
    public function store(Request $request)
    {
        try {
            $request->validate([
                'producto' => 'required|string',
                'precio_nuevo' => 'required|numeric|min:0.01',
                'unidad' => 'required|string',
                'categoria_sugerida' => 'required|string',
                'telegram_user_id' => 'required|string',
            ]);

            // 1. Buscar la categoría por nombre, o usar "Sin categoría" si no existe
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

            // 2. Buscar o crear el producto
            $producto = Producto::firstOrNew(['nombre' => $request->producto]);
            $precioAnterior = $producto->precio_actual ?? 0;

            $producto->categoria_id = $categoria->id;
            $producto->unidad = $request->unidad;
            $producto->precio_actual = $request->precio_nuevo;
            $producto->margen_pct = $producto->margen_pct ?? 40;
            $producto->precio_venta_calculado = $producto->precio_actual * (1 + $producto->margen_pct / 100);
            $producto->save();

            // 3. Registrar la actualización (SIN gasto, solo trazabilidad)
            $actualizacion = ActualizacionPrecio::create([
                'producto_id' => $producto->id,
                'precio_anterior' => $precioAnterior,
                'precio_nuevo' => $request->precio_nuevo,
                'telegram_user_id' => $request->telegram_user_id,
            ]);

            return response()->json([
                'message' => 'Precio actualizado correctamente',
                'producto' => $producto,
                'actualizacion' => $actualizacion,
            ], 200);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
