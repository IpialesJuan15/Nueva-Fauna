<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Inicio de sesión
    public function login(Request $request)
    {
        $request->validate([
            'user_email' => 'required|email',
            'password' => 'required|string',
        ]);

        $credentials = [
            'user_email' => $request->user_email,
            'password' => $request->password,
        ];

        if (!Auth::attempt($credentials)) {
            return response()->json(['error' => 'Credenciales inválidas'], 401);
        }

        $user = Auth::user();

        // Generar el token
        $tokenResult = $user->createToken('Personal Access Token');

        return response()->json([
            'message' => 'Inicio de sesión exitoso',
            'token' => $tokenResult->accessToken, // Devuelve el token de acceso
            'user' => [
                'id' => $user->id,
                'name' => $user->user_nombre,
                'email' => $user->user_email,
            ],
        ]);
    }


    // Cierre de sesión
    public function logout()
    {
        //Auth::user()->tokens()->delete();
        return response()->json(['message' => 'Cierre de sesión exitoso']);
    }
}
