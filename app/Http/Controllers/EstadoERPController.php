<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EstadoERPController extends Controller
{
    public function index()
    {
        $estado = \App\Models\EstadoERP::firstOrCreate([], ['nivel' => 'Básico']);
        return response()->json($estado);
    }

    public function update(Request $request)
    {
        $request->validate([
            'nivel' => 'required|in:Básico,Integrado,Automatizado,Optimizado'
        ]);

        $estado = \App\Models\EstadoERP::firstOrCreate([], ['nivel' => 'Básico']);
        $estado->nivel = $request->nivel;
        $estado->save();

        return response()->json($estado);
    }
}
