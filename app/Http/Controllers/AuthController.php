<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // Validar datos
        $request->validate([
            "email" => "required|email",
            "password" => "required"
        ]);

        // Buscar usuario
        $user = User::where("email", $request->email)->first();

        // Verificar contraseña
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                "message" => "Credenciales incorrectas"
            ], 401);
        }

        // Crear token
        $token = $user->createToken("token")->plainTextToken;

        return response()->json([
            "message" => "Login exitoso",
            "token" => $token,
            "user" => $user
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            "message" => "Sesión cerrada correctamente"
        ]);
    }

    public function register(Request $request)
    {
        // Validar datos
        $request->validate([
            "name" => "required|string|max:255",
            "email" => "required|email|unique:users,email",
            "password" => "required|string|min:6"
        ]);

        // Crear usuario
        $user = User::create([
            "name" => $request->name,
            "email" => $request->email,
            "password" => Hash::make($request->password)
        ]);

        // Crear token
        $token = $user->createToken("token")->plainTextToken;

        return response()->json([
            "message" => "Registro exitoso",
            "token" => $token,
            "user" => $user
        ], 201);
    }
}
