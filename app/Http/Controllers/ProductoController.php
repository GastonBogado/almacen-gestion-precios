<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Producto;
use Illuminate\Database\QueryException;
use App\Models\ProductoProveedor;
use App\Models\Proveedor;
use App\Models\Categoria;
class ProductoController extends Controller
{
    public function delete($id)
    {
        $producto = Producto::findOrFail($id);

        try {
            $producto->delete();
        } catch (QueryException $e) {
            return redirect()->route('dashboard')->with('error', 'No se pudo borrar "' . $producto->nombre . '": tiene actualizaciones de precio o gastos asociados.');
        }

        return redirect()->route('dashboard')->with('success', 'Producto eliminado correctamente.');
    }


   public function store(Request $request)
{
    $datos = $request->validate([
        'nombre' => 'required|string|max:255',
        'categoria_id' => 'required|exists:categoria,id',
        'precio' => 'required|numeric|min:0',
        'unidad' => 'required|string|max:50',
        'margen_pct' => 'required|numeric|min:0|max:100',
        'proveedor_id' => 'required|exists:proveedores,id',
    ]);

    // Buscar si ya existe un producto con ese nombre (case-insensitive)
    $producto = Producto::whereRaw('LOWER(nombre) = ?', [strtolower($datos['nombre'])])->first();

    if (!$producto) {
        $producto = Producto::create([
            'nombre' => $datos['nombre'],
            'unidad' => $datos['unidad'],
            'categoria_id' => $datos['categoria_id'],
            'margen_pct' => $datos['margen_pct'],
            'precio_actual' => $datos['precio'],
            'precio_venta_calculado' => $datos['precio'] * (1 + $datos['margen_pct'] / 100),
        ]);
    }

    // Con producto ya sea nuevo o existente, asociamos/actualizamos el proveedor
    ProductoProveedor::updateOrCreate(
        ['producto_id' => $producto->id, 'proveedor_id' => $datos['proveedor_id']],
        ['precio' => $datos['precio']]
    );

    // Recalcular con el mínimo entre TODOS los proveedores de este producto
    $producto->precio_actual = ProductoProveedor::where('producto_id', $producto->id)->min('precio');
    $producto->precio_venta_calculado = $producto->precio_actual * (1 + $producto->margen_pct / 100);
    $producto->save();

    return redirect()->route('dashboard')->with('success', 'Producto creado correctamente.');
}
    public function edit($id)
    {
      $producto = Producto::with(['categoria', 'productoProveedores.proveedor'])->findOrFail($id);
    $categorias = Categoria::all();
    $proveedores = Proveedor::all();

    return view('productos.edit', compact('producto', 'categorias', 'proveedores'));
    }
    public function update(Request $request, $id)
{
    $datos = $request->validate([
        'nombre' => 'required|string|max:255',
        'unidad' => 'required|string|max:50',
        'categoria_id' => 'required|exists:categoria,id',
        'margen_pct' => 'required|numeric|min:0|max:100',
    ]);

    $producto = Producto::findOrFail($id);
    $producto->fill($datos);
    $producto->precio_venta_calculado = $producto->precio_actual * (1 + $producto->margen_pct / 100);
    $producto->save();

    return redirect()->route('productos.edit', $producto->id)->with('success', 'Producto actualizado correctamente.');
}
}
