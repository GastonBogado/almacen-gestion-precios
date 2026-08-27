<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductoProveedor;
use App\Models\Producto;
class ProductoProveedorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, string $id)
{
    $datos = $request->validate([
        'proveedor_id' => 'required|exists:proveedores,id',
        'precio' => 'required|numeric|min:0.01',
    ]);

    ProductoProveedor::create([
        'producto_id' => $id,
        'proveedor_id' => $datos['proveedor_id'],
        'precio' => $datos['precio'],
    ]);

    // Recalcular el producto padre: mínimo precio entre TODOS sus proveedores
    $producto = Producto::findOrFail($id);
    $producto->precio_actual = ProductoProveedor::where('producto_id', $producto->id)->min('precio');
    $producto->precio_venta_calculado = $producto->precio_actual * (1 + $producto->margen_pct / 100);
    $producto->save();

    return redirect()->route('productos.edit', $producto->id)->with('success', 'Proveedor agregado correctamente.');
}

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
{
    $datos = $request->validate([
        'precio' => 'required|numeric|min:0.01',
    ]);

    $productoProveedor = ProductoProveedor::findOrFail($id);
    $productoProveedor->update($datos);

    // Recalcular el producto padre: mínimo precio entre TODOS sus proveedores
    $producto = $productoProveedor->producto;
    $producto->precio_actual = ProductoProveedor::where('producto_id', $producto->id)->min('precio');
    $producto->precio_venta_calculado = $producto->precio_actual * (1 + $producto->margen_pct / 100);
    $producto->save();

    return redirect()->route('productos.edit', $producto->id)->with('success', 'Precio actualizado correctamente.');
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
