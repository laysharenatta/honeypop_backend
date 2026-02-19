<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    public function index()
    {
        return response()->json(Producto::with('proveedor')->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required',
            'categoria' => 'required',
            'stock_actual' => 'required|integer',
            'stock_minimo' => 'required|integer',
            'proveedor_id' => 'required|exists:proveedors,id',
            'costo_unitario' => 'required|numeric',
        ]);

        $producto = Producto::create($request->all());
        return response()->json($producto, 201);
    }

    public function update(Request $request, Producto $producto)
    {
        $producto->update($request->all());
        return response()->json($producto);
    }

    public function destroy(Producto $producto)
    {
        $producto->delete();
        return response()->json(null, 204);
    }
}
