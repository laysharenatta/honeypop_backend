<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\MovimientoInventario;

class MovimientoInventarioController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'producto_id' => 'required|exists:productos,id',
            'tipo' => 'required|in:entrada,salida',
            'cantidad' => 'required|integer|min:1',
            'motivo' => 'required|in:venta,reposición,ajuste'
        ]);

        $producto = Producto::findOrFail($request->producto_id);

        // Si es ENTRADA
        if ($request->tipo === 'entrada') {
            $producto->stock_actual += $request->cantidad;
        } 
        // Si es SALIDA
        else {
            if ($producto->stock_actual < $request->cantidad) {
                return response()->json([
                    'error' => 'Stock insuficiente'
                ], 400);
            }

            $producto->stock_actual -= $request->cantidad;
        }

        $producto->save();

        $movimiento = MovimientoInventario::create([
            'producto_id' => $request->producto_id,
            'tipo' => $request->tipo,
            'cantidad' => $request->cantidad,
            'motivo' => $request->motivo,
            'fecha' => now()
        ]);

        return response()->json($movimiento, 201);
    }

public function movements(Producto $producto)
    {
        $movements = $producto->movimientos()->orderBy('fecha', 'desc')->get(); 
        return response()->json($movements);
    }

}