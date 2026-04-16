<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePromocionRequest;
use App\Http\Requests\UpdatePromocionRequest;
use App\Models\Promocion;
use Illuminate\Http\Request;

class PromocionController extends Controller
{
    public function index()
    {
        $promociones = Promocion::all();
        return response()->json($promociones);
    }

    public function show($id)
    {
        $promocion = Promocion::findOrFail($id);
        $promocion->load('productos');
        return response()->json($promocion);
    }

    public function store(StorePromocionRequest $request)
    {
        $data = $request->validated();
        $promocion = Promocion::create($data);

        // Attach products if provided
        if ($request->has('producto_ids')) {
            $promocion->productos()->sync($request->producto_ids);
        }

        return response()->json($promocion, 201);
    }

    public function update(UpdatePromocionRequest $request, $id)
    {
        $promocion = Promocion::findOrFail($id);
        $data = $request->validated();
        $promocion->update($data);

        // Update products if provided
        if ($request->has('producto_ids')) {
            $promocion->productos()->sync($request->producto_ids);
        }

        return response()->json($promocion);
    }

    public function destroy($id)
    {
        $promocion = Promocion::findOrFail($id);
        $promocion->delete();
        return response()->json([], 204);
    }
}
