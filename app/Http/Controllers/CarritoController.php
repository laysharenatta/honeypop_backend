<?php

namespace App\Http\Controllers;

use App\Models\Carrito;
use App\Models\CarritoDetalle;
use App\Models\Producto;
use App\Models\Pedido;
use App\Models\Pago;
use App\Models\MovimientoInventario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CarritoController extends Controller
{
    /**
     * Helper para obtener el carrito activo del cliente autenticado.
     */
    private function getActiveCart()
    {
        $clientId = auth()->id();
        return Carrito::firstOrCreate(
            ['cliente_id' => $clientId, 'estado' => 'activo']
        );
    }

    /**
     * GET /cliente/carrito
     */
    public function show()
    {
        $carrito = $this->getActiveCart();
        $carrito->load('detalles.producto');

        $subtotal = 0;
        foreach ($carrito->detalles as $detalle) {
            $subtotal += $detalle->cantidad * $detalle->precio_unitario;
        }

        $impuestos = $subtotal * 0.16;
        $total = $subtotal + $impuestos;

        return response()->json([
            'carrito' => $carrito,
            'subtotal' => $subtotal,
            'impuestos' => $impuestos,
            'total' => $total
        ]);
    }

    /**
     * POST /cliente/carrito/agregar
     */
    public function agregar(Request $request)
    {
        $request->validate([
            'producto_id' => 'required|exists:productos,id',
            'cantidad' => 'required|integer|min:1',
        ]);

        $carrito = $this->getActiveCart();
        $producto = Producto::findOrFail($request->producto_id);

        $detalle = CarritoDetalle::where('carrito_id', $carrito->id)
            ->where('producto_id', $producto->id)
            ->first();

        if ($detalle) {
            $detalle->cantidad += $request->cantidad;
            $detalle->save();
        } else {
            CarritoDetalle::create([
                'carrito_id' => $carrito->id,
                'producto_id' => $producto->id,
                'cantidad' => $request->cantidad,
                'precio_unitario' => $producto->costo_unitario // o el precio real del producto
            ]);
        }

        return response()->json(['message' => 'Producto agregado al carrito'], 201);
    }

    /**
     * PUT /cliente/carrito/item/{item_id}
     */
    public function updateItem(Request $request, $item_id)
    {
        $request->validate([
            'cantidad' => 'required|integer|min:1',
        ]);

        $carrito = $this->getActiveCart();
        $detalle = CarritoDetalle::where('carrito_id', $carrito->id)->findOrFail($item_id);
        
        $detalle->cantidad = $request->cantidad;
        $detalle->save();

        return response()->json(['message' => 'Cantidad actualizada']);
    }

    /**
     * DELETE /cliente/carrito/item/{item_id}
     */
    public function removeItem($item_id)
    {
        $carrito = $this->getActiveCart();
        $detalle = CarritoDetalle::where('carrito_id', $carrito->id)->findOrFail($item_id);
        $detalle->delete();

        return response()->json(['message' => 'Producto removido del carrito']);
    }

    /**
     * DELETE /cliente/carrito/vaciar
     */
    public function vaciar()
    {
        $carrito = $this->getActiveCart();
        $carrito->detalles()->delete();

        return response()->json(['message' => 'Carrito vaciado exitosamente']);
    }

    /**
     * POST /cliente/carrito/comprar
     */
    public function comprar(Request $request)
    {
        $request->validate([
            'metodo_pago' => 'required|string',
        ]);

        $carrito = $this->getActiveCart();
        $detalles = $carrito->detalles()->with('producto')->get();

        if ($detalles->isEmpty()) {
            return response()->json(['message' => 'El carrito está vacío'], 400);
        }

        DB::beginTransaction();

        try {
            $pagosRegistrados = [];

            foreach ($detalles as $detalle) {
                // Bloqueo de producto para evitar race conditions en stock
                $producto = Producto::lockForUpdate()->find($detalle->producto_id);

                if ($producto->stock_actual < $detalle->cantidad) {
                    throw new \Exception('Stock insuficiente para el producto: ' . $producto->nombre);
                }

                $subtotalItem = $detalle->precio_unitario * $detalle->cantidad;
                $impuestoItem = $subtotalItem * 0.16;
                $totalItem = $subtotalItem + $impuestoItem;

                // Crear el Pedido (1 a 1 por producto como acordado)
                $pedido = Pedido::create([
                    'producto_id' => $producto->id,
                    'cantidad' => $detalle->cantidad,
                    'tipo' => 'venta',
                    'estado' => 'surtido',
                ]);

                // Descontar inventario
                $producto->stock_actual -= $detalle->cantidad;
                $producto->save();

                MovimientoInventario::create([
                    'producto_id' => $producto->id,
                    'tipo' => 'salida',
                    'cantidad' => $detalle->cantidad,
                    'motivo' => 'venta',
                    'fecha' => now()
                ]);

                // Registrar el Pago
                $pago = Pago::create([
                    'pedido_id' => $pedido->id,
                    'monto' => $totalItem,
                    'metodo_pago' => $request->metodo_pago,
                    'estado' => 'completado',
                    'fecha' => now()
                ]);

                $pagosRegistrados[] = $pago;
            }

            // Cambiar estado del carrito y limpiarlo lógicamente
            $carrito->estado = 'comprado';
            $carrito->save();

            DB::commit();

            return response()->json([
                'message' => 'Compra realizada exitosamente',
                'pagos' => $pagosRegistrados
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Error al procesar la compra',
                'error' => $e->getMessage()
            ], 400);
        }
    }
}
