<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use App\Models\TipoUsuario;

class UserController extends Controller
{
    public function register(Request $request)
    {
        // Validar los datos del formulario
        $request->validate([
            'user_nombre' => 'required|string|max:50',
            'user_apellido' => 'required|string|max:50',
            'user_email' => 'required|email|unique:usuarios,user_email|max:35',
            'user_telefono' => 'required|string|max:10',
            'password' => 'required|string|min:6|confirmed',
        ]);

        // Obtener el tipo de usuario 'USER'
        $tipoUsuario = TipoUsuario::where('tipus_detalles', 'USER')->first();
        if (!$tipoUsuario) {
            return back()->withErrors(['error' => 'No se encontró el tipo de usuario USER.']);
        }

        // Crear el usuario
        Usuario::create([
            'tipus_id' => $tipoUsuario->tipus_id,
            'user_nombre' => $request->user_nombre,
            'user_apellido' => $request->user_apellido,
            'user_email' => $request->user_email,
            'user_password' => $request->password,
            'user_telefono' => $request->user_telefono,
            'user_estado' => true, // Usuario activo por defecto
        ]);

        // Redirigir con un mensaje de éxito
        return redirect('/login')->with('success', 'Usuario registrado con éxito.');
    }
}
