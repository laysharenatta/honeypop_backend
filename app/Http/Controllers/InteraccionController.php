<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Interaccion;
use App\Models\Client;


class InteraccionController extends Controller
{
    // ✅ POST /interacciones
    public function store(Request $request)
    {
        // Validación
        $request->validate([
            'cliente_id' => 'required|exists:clients,id',
            'tipo' => 'required|in:llamada,correo,reunion',
            'descripcion' => 'required|string',
            'fecha' => 'required|date'
        ]);

        // Crear interacción
        $interaccion = Interaccion::create([
            'cliente_id' => $request->cliente_id,
            'tipo' => $request->tipo,
            'descripcion' => $request->descripcion,
            'fecha' => $request->fecha,
            'usuario_id' => null // luego lo conectamos con auth si quieres
        ]);

        return response()->json([
            'mensaje' => 'Interacción registrada correctamente',
            'data' => $interaccion
        ], 201);
    }

// ✅ GET /clientes/{id}/interacciones
public function historial($id)
{
    $cliente = Client::findOrFail($id);

    $interacciones = $cliente->interacciones()
        ->orderBy('fecha', 'desc')
        ->get();

    return response()->json([
        'cliente' => $cliente->nombre,
        'interacciones' => $interacciones
    ]);
}


}
