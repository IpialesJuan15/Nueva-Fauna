<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\TipoUsuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function register(Request $request)
    {
        // Validación
        $request->validate([
            'tipus_id' => 'required|exists:tipos_usuarios,tipus_id',
            'user_cedula' => 'required|size:10|unique:usuarios,user_cedula',
            'user_nombre' => 'required|string|max:50',
            'user_apellido' => 'required|string|max:50',
            'user_email' => 'required|email|unique:usuarios,user_email',
            'user_password' => 'required|string|min:6|confirmed',
            'user_telefono' => 'required|size:10',
        ]);


        // Crear el usuario
        // Crear usuario
        Usuario::create([
            'tipus_id' => $request->tipus_id,
            'user_cedula' => $request->user_cedula,
            'user_nombre' => $request->user_nombre,
            'user_apellido' => $request->user_apellido,
            'user_email' => $request->user_email,
            'user_password' => Hash::make($request->user_password), // Asegúrate de cifrar
            'user_telefono' => $request->user_telefono,
            'user_estado' => true,
        ]);

        return redirect('/home')->with('success', 'Usuario registrado con éxito');
    }

    public function login(Request $request)
    {
        // Validación
        $request->validate([
            'user_email' => 'required|email',
            'user_password' => 'required|string',
        ]);

        $user = Usuario::where('user_email', $request->user_email)->first();

        if (!$user || !Hash::check($request->user_password, $user->user_password)) {
            return response()->json(['error' => 'Credenciales inválidas'], 401);
        }



        // Redirección según el tipo de usuario
        switch ($user->tipoUsuario->tipus_detalles) {
            case 'USER':
                $redirect = '/observador';
                break;
            case 'TAX':
                $redirect = '/taxonomo';
                break;
            case 'INVEST':
                $redirect = '/FormInvest';
                break;
            default:
                $redirect = '/';
        }

        return response()->json([
            'message' => 'Inicio de sesión exitoso',
            'redirect' => $redirect,
        ]);
    }
}
