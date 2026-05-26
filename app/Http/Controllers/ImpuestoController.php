<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreImpuestoRequest;
use App\Http\Requests\UpdateImpuestoRequest;
use App\Models\Impuesto;
use App\Services\TaxService;
use Illuminate\Http\Request;

class ImpuestoController extends Controller
{
    public function index()
    {
        $impuestos = Impuesto::all();
        return response()->json($impuestos);
    }

    public function show($id)
    {
        $impuesto = Impuesto::findOrFail($id);
        return response()->json($impuesto);
    }

    public function store(StoreImpuestoRequest $request)
    {
        $impuesto = Impuesto::create($request->validated());

        return response()->json(
            ['message' => 'Impuesto creado exitosamente', 'data' => $impuesto],
            201
        );
    }

    public function update(UpdateImpuestoRequest $request, $id)
    {
        $impuesto = Impuesto::findOrFail($id);
        $impuesto->update($request->validated());

        return response()->json(['message' => 'Impuesto actualizado exitosamente', 'data' => $impuesto]);
    }

    public function destroy($id)
    {
        $impuesto = Impuesto::findOrFail($id);
        $impuesto->delete();

        return response()->json([], 204);
    }

    /**
     * GET /impuestos/activos
     * Retorna los impuestos activos y la tasa total combinada.
     */
    public function activos()
    {
        $impuestos = TaxService::getActiveImpuestos();
        $tasaTotal = TaxService::getTotalRate();

        return response()->json([
            'impuestos' => $impuestos,
            'tasa_total' => $tasaTotal,
        ]);
    }
}
