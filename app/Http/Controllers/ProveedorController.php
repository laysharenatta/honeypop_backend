<?php

namespace App\Http\Controllers;

use App\Models\Proveedor;
use Illuminate\Http\Request;

class ProveedorController extends Controller
{
    public function index()
    {
        return response()->json(Proveedor::all());
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required',
            'contacto' => 'required',
            'correo' => 'required|email|unique:proveedors',
            'telefono' => 'required',
        ]);

        $proveedor = Proveedor::create($request->all());
        return response()->json($proveedor, 201);
    }

    public function show($id)
    {
        $proveedor = Proveedor::findOrFail($id);
        if (!$proveedor) {
            return response()->json(['message' => 'Proveedor no encontrado'], 404);
        }
        return response()->json($proveedor);
    }

    public function update(Request $request, $id)
    {
        $proveedor = Proveedor::findOrFail($id);
        if (!$proveedor) {
            return response()->json(['message' => 'Proveedor no encontrado'], 404);
        }

        $request->validate([
            'nombre' => 'sometimes|required',
            'contacto' => 'sometimes|required',
            'correo' => 'sometimes|required|email|unique:proveedors,correo,' . $id,
            'telefono' => 'sometimes|required',
        ]);

        $proveedor->update($request->all());
        return response()->json($proveedor, 200);
    }

    public function destroy($id)
    {
        $proveedor = Proveedor::findOrFail($id);
        if (!$proveedor) {
            return response()->json(['message' => 'Proveedor no encontrado'], 404);
        }
        $proveedor->delete();
        return response()->json([], 204);
    }
}
