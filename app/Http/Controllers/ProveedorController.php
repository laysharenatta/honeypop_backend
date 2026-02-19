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
}
