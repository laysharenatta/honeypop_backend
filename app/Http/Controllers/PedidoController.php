<?php

namespace App\Http\Controllers;

use App\Models\MovimientoInventario;
use Illuminate\Http\Request;
use App\Models\Pedido;

class PedidoController extends Controller
{
    public function index()
    {
        $pedidos = Pedido::with('producto')->get();
        return response()->json($pedidos);
    }

    public function store(Request $request)
    {
        $request->validate([
            'producto_id' => 'required|exists:productos,id',
            'cantidad' => 'required|integer|min:1',
            'tipo' => 'required|in:reposicion,venta',
            'estado' => 'nullable|in:pendiente,surtido',
        ]);

        $pedido = Pedido::create($request->all());

        return response()->json($pedido, 201);
    }

    public function updateEstado(Request $request, Pedido $pedido)
    {
        $request->validate([
            'estado' => 'required|in:pendiente,surtido',
        ]);

        $estadoAnterior = $pedido->estado;
        $pedido->update(['estado' => $request->estado]);

        // Logica para actualizar el inventario si el pedido se marca como surtido y es reposicion
        if ($estadoAnterior !== 'surtido' && $pedido->estado === 'surtido' && $pedido->tipo === 'reposicion') {
            $producto = $pedido->producto;
            $producto->stock_actual += $pedido->cantidad;
            $producto->save();

            // Registrar el movimiento de entrada
            MovimientoInventario::create([
                'producto_id' => $producto->id,
                'tipo' => 'entrada',
                'cantidad' => $pedido->cantidad,
                'motivo' => 'reposición',
                'fecha' => now()
            ]);
        }

        return response()->json($pedido);
    }
}
