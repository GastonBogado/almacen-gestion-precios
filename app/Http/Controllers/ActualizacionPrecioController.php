<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Categoria;
use App\Models\ActualizacionPrecio;
use App\Models\Producto;
use App\Models\Proveedor;
use App\Models\ProductoProveedor;

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
                'proveedor_detectado' => 'required|string',
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

            // 2. Buscar o crear el producto por nombre (case-insensitive)
            $producto = Producto::whereRaw('LOWER(nombre) = ?', [strtolower($request->producto)])->first();
            if (!$producto) {
                // precio_actual y precio_venta_calculado son NOT NULL sin default: les damos un valor
                // inicial para que no falle este primer insert. Más abajo se recalculan igual con el
                // mínimo entre proveedores.
                $margenPctInicial = 40;
                $producto = new Producto([
                    'nombre' => $request->producto,
                    'precio_actual' => $request->precio_nuevo,
                    'margen_pct' => $margenPctInicial,
                    'precio_venta_calculado' => $request->precio_nuevo * (1 + $margenPctInicial / 100),
                ]);
            }

            $producto->categoria_id = $categoria->id;
            $producto->unidad = $request->unidad;
            $producto->margen_pct = $producto->margen_pct ?? 40;
            $producto->save();

            // 3. Buscar o crear el proveedor por nombre (case-insensitive).
            // A diferencia de la categoría, acá no hay fallback genérico: cada proveedor real se conserva.
            $proveedor = Proveedor::whereRaw('LOWER(nombre) = ?', [strtolower($request->proveedor_detectado)])->first();
            if (!$proveedor) {
                $proveedor = Proveedor::create(['nombre' => $request->proveedor_detectado]);
            }

            // 4. Upsert del precio de ESE proveedor para ESE producto en producto_proveedores.
            // El "precio anterior" para el historial se toma de este pivot, no de producto.precio_actual.
            $productoProveedor = ProductoProveedor::where('producto_id', $producto->id)
                ->where('proveedor_id', $proveedor->id)
                ->first();

            $precioAnterior = $productoProveedor->precio ?? 0;

            ProductoProveedor::updateOrCreate(
                ['producto_id' => $producto->id, 'proveedor_id' => $proveedor->id],
                ['precio' => $request->precio_nuevo]
            );

            // 4b. precio_actual queda como una vista rápida del mejor precio (el más bajo) entre
            // todos los proveedores cargados para este producto, para que la tarjeta del dashboard
            // (precio_venta_calculado) siga viva sin tener que tocarla "a mano" desde este endpoint.
            $mejorPrecioProveedor = ProductoProveedor::where('producto_id', $producto->id)->min('precio');

            $producto->precio_actual = $mejorPrecioProveedor;
            $producto->precio_venta_calculado = $producto->precio_actual * (1 + $producto->margen_pct / 100);
            $producto->save();

            // 5. Registrar la actualización para trazabilidad, con referencia al proveedor
            $actualizacion = ActualizacionPrecio::create([
                'producto_id' => $producto->id,
                'proveedor_id' => $proveedor->id,
                'precio_anterior' => $precioAnterior,
                'precio_nuevo' => $request->precio_nuevo,
                'telegram_user_id' => $request->telegram_user_id,
            ]);

            return response()->json([
                'message' => 'Precio actualizado correctamente',
                'producto' => $producto,
                'proveedor' => $proveedor,
                'actualizacion' => $actualizacion,
            ], 200);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
