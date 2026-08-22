<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Database\QueryException;

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
}
