<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Producto;
use App\Models\MovimientoInventario;
use App\Models\Promocion;
use App\Services\TaxService;
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
            $subtotal = 0;
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

                $subtotalProducto = $precioUnitario * $prod['cantidad'];
                $subtotal += $subtotalProducto;

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

            // Calcular impuestos dinámicamente
            $impuestos = TaxService::calcular($subtotal);

            // Create Order
            $order = Order::create([
                'cliente_id' => $request->cliente_id,
                'user_id' => $request->user_id,
                'fecha' => now()->toDateString(),
                'estado' => false, // false as pending
                'subtotal' => $impuestos['subtotal'],
                'impuesto_monto' => $impuestos['impuesto_monto'],
                'impuesto_porcentaje' => $impuestos['impuesto_porcentaje'],
                'total' => $impuestos['total'],
            ]);

            // Attach products
            $order->productos()->attach($productosToAttach);

            DB::commit();

            $order->load(['client', 'user', 'productos']);

            return response()->json([
                'order' => $order,
                'impuestos_aplicados' => $impuestos['impuestos_aplicados'],
            ], 201);

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
