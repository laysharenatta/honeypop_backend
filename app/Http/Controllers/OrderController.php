<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Producto;
use App\Models\MovimientoInventario;
use App\Models\Promocion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * GET /ordenes
     */
    public function index()
    {
        $orders = Order::with(['client', 'user', 'productos'])->get();
        return response()->json($orders);
    }

    /**
     * GET /ordenes/{id}
     */
    public function show($id)
    {
        $order = Order::with(['client', 'user', 'productos'])->findOrFail($id);
        return response()->json($order);
    }

    /**
     * POST /ordenes
     */
    public function store(Request $request)
    {
        $request->validate([
            'cliente_id' => 'required|exists:clients,id',
            'user_id' => 'required|exists:users,id',
            'productos' => 'required|array|min:1',
            'productos.*.id' => 'required|exists:productos,id',
            'productos.*.cantidad' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();

        try {
            $total = 0;
            $productosToAttach = [];

            // Validate and calculate real total using the actual product cost
            foreach ($request->productos as $prod) {
                $producto = Producto::lockForUpdate()->find($prod['id']);

                if ($producto->stock_actual < $prod['cantidad']) {
                    return response()->json(['message' => 'Stock insuficiente para el producto: ' . $producto->nombre], 400);
                }

                $precioUnitario = $producto->costo_unitario; // Using costo unitario as precio

                // Apply active promotions to the product
                $precioUnitario = $this->aplicarDescuentosPromocion($producto, $precioUnitario);

                $subtotal = $precioUnitario * $prod['cantidad'];
                $total += $subtotal;

                // Prepare pivot data
                $productosToAttach[$producto->id] = [
                    'cantidad' => $prod['cantidad'],
                    'precio_unitario' => $precioUnitario
                ];

                // Inventory Logic: decrement stock securely
                $producto->stock_actual -= $prod['cantidad'];
                $producto->save();

                // Register movement
                MovimientoInventario::create([
                    'producto_id' => $producto->id,
                    'tipo' => 'salida',
                    'cantidad' => $prod['cantidad'],
                    'motivo' => 'venta',
                    'fecha' => now()
                ]);
            }

            // Create Order
            $order = Order::create([
                'cliente_id' => $request->cliente_id,
                'user_id' => $request->user_id,
                'fecha' => now()->toDateString(),
                'estado' => false, // false as pending
                'total' => $total,
            ]);

            // Attach products
            $order->productos()->attach($productosToAttach);

            DB::commit();

            return response()->json($order->load(['client', 'user', 'productos']), 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error al crear la orden', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * PUT /ordenes/{id}/estado
     */
    public function updateEstado(Request $request, $id)
    {
        $request->validate([
            'estado' => 'required|boolean',
        ]);

        $order = Order::findOrFail($id);

        $order->update([
            'estado' => $request->estado,
        ]);

        return response()->json($order);
    }

    /**
     * Apply the best active promotion discount to a product price
     * Returns the price with the maximum discount applied
     */
    private function aplicarDescuentosPromocion(Producto $producto, $precioUnitario)
    {
        $today = now()->toDateString();

        // Get all active promotions for this product
        $promocionesActivas = $producto->promociones()
            ->where('estado', 'activa')
            ->where('fecha_inicio', '<=', $today)
            ->where('fecha_fin', '>=', $today)
            ->get();

        if ($promocionesActivas->isEmpty()) {
            return $precioUnitario;
        }

        // Calculate all possible discounts and apply the maximum
        $descuentoMaximo = 0;

        foreach ($promocionesActivas as $promocion) {
            if ($promocion->tipo_descuento === 'porcentaje') {
                $descuento = ($precioUnitario * $promocion->valor) / 100;
            } else { // monto_fijo
                $descuento = $promocion->valor;
            }

            if ($descuento > $descuentoMaximo) {
                $descuentoMaximo = $descuento;
            }
        }

        return $precioUnitario - $descuentoMaximo;
    }
}
