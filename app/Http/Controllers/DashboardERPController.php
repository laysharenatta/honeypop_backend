<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardERPController extends Controller
{
    public function index()
    {
        $ventasTotales = \App\Models\Order::sum('total');
        $ordenesProcesadas = \App\Models\Order::count();
        $productosVendidos = \Illuminate\Support\Facades\DB::table('order_product')->sum('cantidad');
        $clientesActivos = \App\Models\Client::where('estado', true)->count();
        $inventarioDisponible = \App\Models\Producto::sum('stock_actual');

        return response()->json([
            'ventas_totales' => $ventasTotales,
            'ordenes_procesadas' => $ordenesProcesadas,
            'productos_vendidos' => $productosVendidos,
            'clientes_activos' => $clientesActivos,
            'inventario_disponible' => $inventarioDisponible
        ]);
    }
}
