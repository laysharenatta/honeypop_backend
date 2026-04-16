<?php

namespace App\Http\Controllers;

use App\Models\AtencionTicket;
use App\Http\Requests\StoreAtencionTicketRequest;
use App\Http\Requests\UpdateAtencionTicketRespuestaRequest;
use Illuminate\Http\Request;

class AtencionTicketController extends Controller
{
    /**
     * GET /atencion/tickets
     * Retorna todos los tickets del cliente autenticado
     */
    public function index(Request $request)
    {
        $tickets = AtencionTicket::where('cliente_id', $request->user()->id)
            ->with('cliente')
            ->orderBy('fecha', 'desc')
            ->get();

        return response()->json($tickets);
    }

    /**
     * POST /atencion/tickets
     * Crea un nuevo ticket de atención
     */
    public function store(StoreAtencionTicketRequest $request)
    {
        // Crear ticket con datos validados del cliente autenticado
        $ticket = AtencionTicket::create([
            'cliente_id' => $request->user()->id,
            'asunto' => $request->asunto,
            'mensaje' => $request->mensaje,
            'estado' => 'abierto',
            'fecha' => now(),
        ]);

        return response()->json([
            'mensaje' => 'Ticket de atención creado correctamente',
            'data' => $ticket
        ], 201);
    }

    /**
     * GET /atencion/tickets/{id}
     * Retorna un ticket específico del cliente autenticado
     */
    public function show(Request $request, $id)
    {
        $ticket = AtencionTicket::where('cliente_id', $request->user()->id)
            ->findOrFail($id);

        return response()->json($ticket);
    }

    /**
     * PUT /atencion/tickets/{id}/respuesta
     * Actualiza la respuesta y cambia el estado a respondido
     */
    public function updateRespuesta(UpdateAtencionTicketRespuestaRequest $request, $id)
    {
        $ticket = AtencionTicket::where('cliente_id', $request->user()->id)
            ->findOrFail($id);

        $ticket->update([
            'respuesta' => $request->respuesta,
            'estado' => 'respondido',
        ]);

        return response()->json([
            'mensaje' => 'Respuesta registrada correctamente',
            'data' => $ticket
        ]);
    }
}
