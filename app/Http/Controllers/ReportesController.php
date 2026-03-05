<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportesController extends Controller
{
    public function productosMasVendidos(Request $request)
    {
        $query = Pedido::where('tipo', 'venta');

        if ($request->has('fecha_inicio')) {
            $query->whereDate('created_at', '>=', $request->fecha_inicio);
        }
        if ($request->has('fecha_fin')) {
            $query->whereDate('created_at', '<=', $request->fecha_fin);
        }

        $productos = $query->select('producto_id', DB::raw('SUM(cantidad) as total_vendido'))
            ->groupBy('producto_id')
            ->orderByDesc('total_vendido')
            ->with(['producto' => function ($query) {
                $query->select('id', 'nombre', 'categoria');
            }])
            ->get();

        // Mapear para una respuesta más limpia
        $data = $productos->map(function ($item) {
            return [
                'producto_id' => $item->producto_id,
                'nombre' => $item->producto->nombre ?? 'Desconocido',
                'categoria' => $item->producto->categoria ?? 'Sin categoría',
                'total_vendido' => (int) $item->total_vendido
            ];
        });

        return response()->json($data);
    }

    public function inventarioCritico()
    {
        $productos = Producto::whereRaw('stock_actual <= stock_minimo')
            ->select('id', 'nombre', 'stock_actual', 'stock_minimo', 'categoria')
            ->get();

        return response()->json($productos);
    }

    public function rotacionLenta(Request $request)
    {
        $dias = $request->input('dias', 30);
        $fechaLimite = Carbon::now()->subDays($dias);
        $umbralVentas = $request->input('umbral', 10);

        $ventas = Pedido::where('tipo', 'venta')
            ->where('created_at', '>=', $fechaLimite)
            ->select('producto_id', DB::raw('SUM(cantidad) as total_vendido'))
            ->groupBy('producto_id')
            ->get()
            ->pluck('total_vendido', 'producto_id');

        $productos = Producto::select('id', 'nombre', 'stock_actual', 'categoria')
            ->get()
            ->map(function ($producto) use ($ventas) {
                $totalVendido = $ventas[$producto->id] ?? 0;
                return [
                    'id' => $producto->id,
                    'nombre' => $producto->nombre,
                    'categoria' => $producto->categoria,
                    'stock_actual' => $producto->stock_actual,
                    'total_vendido_periodo' => (int) $totalVendido
                ];
            })
            ->filter(function ($item) use ($umbralVentas) {
                return $item['total_vendido_periodo'] < $umbralVentas;
            })
            ->values();

        return response()->json($productos);
    }

    public function conteoEstrategias()
    {
        $conteos = Producto::select('estrategia_logistica', DB::raw('count(*) as total'))
            ->groupBy('estrategia_logistica')
            ->get();

        return response()->json($conteos);
    }
}
