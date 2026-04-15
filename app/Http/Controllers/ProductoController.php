<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    public function index(Request $request)
    {
        $query = Producto::with('proveedor');

        if ($request->has('categoria')) {
            $query->where('categoria', $request->query('categoria'));
        }

        if ($request->has('buscar')) {
            $query->where('nombre', 'like', '%' . $request->query('buscar') . '%');
        }

        if ($request->has('estrategia')) {
            $query->where('estrategia_logistica', $request->query('estrategia'));
        }

        return response()->json($query->get());
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
            'estrategia_logistica' => 'nullable|in:PUSH,PULL',
        ]);

        $producto = Producto::create($request->all());
        return response()->json($producto, 201);
    }

        public function show($id)
        {
            $producto = Producto::with('proveedor')->find($id);

            if (!$producto) {
                return response()->json(['message' => 'Producto no encontrado'], 404);
            }

            return response()->json($producto);
        }

    public function update(Request $request, Producto $producto)
    {
        $producto->update($request->all());
        return response()->json($producto);
    }

    public function updateEstrategia(Request $request, Producto $producto)
    {
        $producto->update(['estrategia_logistica' => $request->estrategia_logistica]);

        return response()->json($producto);
    }

    public function destroy(Producto $producto)
    {
        $producto->delete();
        return response()->json(null, 204);
    }
}
