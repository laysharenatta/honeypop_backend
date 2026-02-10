<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use Carbon\Carbon;


class MetricasController extends Controller
{
   public function index()
{
    // 1. Total de clientes
    $totalClientes = Client::count();

    // 2. Clientes activos
    $clientesActivos = Client::where("estado", true)->count();

    // 3. Clientes inactivos
    $clientesInactivos = Client::where("estado", false)->count();

    // 4. Número de interacciones por cliente
    $interaccionesPorCliente = Client::withCount("interacciones")
        ->get()
        ->map(function ($cliente) {
            return [
                "id" => $cliente->id,
                "nombre" => $cliente->nombre,
                "total_interacciones" => $cliente->interacciones_count
            ];
        });

    // 5. Clientes sin interacción reciente (últimos 30 días)
    $fechaLimite = Carbon::now()->subDays(30);

    $clientesEnRiesgo = Client::whereDoesntHave("interacciones", function ($query) use ($fechaLimite) {
        $query->where("fecha", ">=", $fechaLimite);
    })->get();

    // Respuesta final JSON
    return response()->json([
        "total_clientes" => $totalClientes,
        "activos" => $clientesActivos,
        "inactivos" => $clientesInactivos,
        "interacciones_por_cliente" => $interaccionesPorCliente,
        "clientes_en_riesgo" => $clientesEnRiesgo
    ]);
}

}
