<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Interaccion;
use App\Models\Client;

class InteraccionController extends Controller
{

    public function index()
    {
        $interacciones = Interaccion::with('cliente', 'usuario')
            ->orderBy('fecha', 'desc')
            ->get();

        return response()->json($interacciones);
    }
    // ✅ POST /interacciones
    public function store(Request $request)
    {
        // ✅ Validación
        $request->validate([
            'cliente_id' => 'required|exists:clients,id',
            'tipo' => 'required|in:llamada,correo,reunion',
            'descripcion' => 'required|string',
            'fecha' => 'required|date'
        ]);

        // ✅ Crear interacción con usuario autenticado
        $interaccion = Interaccion::create([
            'cliente_id' => $request->cliente_id,
            'tipo' => $request->tipo,
            'descripcion' => $request->descripcion,
            'fecha' => $request->fecha,

            // ✅ Usuario responsable automático
            'usuario_id' => $request->user()->id
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
