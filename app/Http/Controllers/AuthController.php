<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // ✅ LOGIN
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

    // ✅ LOGOUT
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            "message" => "Sesión cerrada correctamente"
        ]);
    }
}
