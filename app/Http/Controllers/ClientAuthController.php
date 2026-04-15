<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ClientAuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'correo'   => 'required|email',
            'password' => 'required',
        ]);

        $client = Client::where('correo', $request->correo)->first();

        if (!$client || !Hash::check($request->password, $client->password)) {
            return response()->json([
                'message' => 'Credenciales incorrectas',
            ], 401);
        }

        $token = $client->createToken('client-token')->plainTextToken;

        return response()->json([
            'message' => 'Login exitoso',
            'token'   => $token,
            'client'  => $client,
        ]);
    }

    public function register(Request $request)
    {
        $request->validate([
            'nombre'   => 'required|string|max:255',
            'correo'   => 'required|email|unique:clients,correo',
            'password' => 'required|string|min:6',
            'telefono' => 'nullable|string|max:20',
            'empresa'  => 'nullable|string|max:255',
        ]);

        $client = Client::create([
            'nombre'          => $request->nombre,
            'correo'          => $request->correo,
            'password'        => Hash::make($request->password),
            'telefono'        => $request->telefono,
            'empresa'         => $request->empresa,
            'fecha_registro'  => now(),
            'estado'          => true,
        ]);

        $token = $client->createToken('client-token')->plainTextToken;

        return response()->json([
            'message' => 'Registro exitoso',
            'token'   => $token,
            'client'  => $client,
        ], 201);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'Sesión cerrada correctamente',
        ]);
    }

    public function me(Request $request)
    {
        return response()->json($request->user());
    }
}
