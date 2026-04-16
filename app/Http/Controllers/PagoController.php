<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use App\Models\Pedido;
use App\Models\Producto;
use App\Models\MovimientoInventario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PagoController extends Controller
{
    /**
     * POST /pagos/procesar
     * Crear un pedido de 1 solo producto simulando un proceso de pago.
     */
    public function procesar(Request $request)
    {
        $request->validate([
            'producto_id' => 'required|exists:productos,id',
            'cantidad' => 'required|integer|min:1',
            'metodo_pago' => 'required|string',
        ]);

        DB::beginTransaction();

        try {
            $producto = Producto::lockForUpdate()->find($request->producto_id);

            // Validar stock
            if ($producto->stock_actual < $request->cantidad) {
                return response()->json([
                    'message' => 'Stock insuficiente para el producto: ' . $producto->nombre
                ], 400);
            }

            // Según la regla actual, se usa el costo del producto como precio base.
            $precioUnitario = $producto->costo_unitario; // u otro campo como 'precio'
            $subtotal = $precioUnitario * $request->cantidad;
            $impuesto = $subtotal * 0.16; // 16% de impuestos
            $total = $subtotal + $impuesto;

            // Generar el Pedido (tipo venta, estado completado/pendiente según lógica)
            $pedido = Pedido::create([
                'producto_id' => $producto->id,
                'cantidad' => $request->cantidad,
                'tipo' => 'venta',
                'estado' => 'surtido', // Corregido de 'completado' a 'surtido' (valor válido en ENUM)
            ]);

            // Descontar inventario
            $producto->stock_actual -= $request->cantidad;
            $producto->save();

            MovimientoInventario::create([
                'producto_id' => $producto->id,
                'tipo' => 'salida',
                'cantidad' => $request->cantidad,
                'motivo' => 'venta', // Corregido de 'venta_cliente' a 'venta' (valor válido en ENUM)
                'fecha' => now()
            ]);

            // Registrar el Pago
            $pago = Pago::create([
                'pedido_id' => $pedido->id,
                'monto' => $total,
                'metodo_pago' => $request->metodo_pago,
                'estado' => 'completado', // simulación de pago exitoso
                'fecha' => now()
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Pago procesado y pedido generado exitosamente.',
                'pago' => $pago->load('pedido.producto')
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Error al procesar el pago',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * GET /pagos/{id}
     */
    public function show($id)
    {
        $pago = Pago::with('pedido.producto')->findOrFail($id);
        return response()->json($pago);
    }
}
