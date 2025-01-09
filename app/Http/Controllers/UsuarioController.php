<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    public function registrar(Request $request)
    {
        // Validación de datos
        $request->validate([
            'user_nombre' => 'required|string|max:50',
            'user_apellido' => 'required|string|max:50',
            'user_email' => 'required|email|unique:usuarios,user_email',
            'user_password' => 'required|string|min:6',
            'user_telefono' => 'nullable|string|max:10',
        ]);

        // Crear usuario con rol 'USER' por defecto
        Usuario::create([
            'tipus_id' => 1, // USER por defecto
            'user_nombre' => $request->user_nombre,
            'user_apellido' => $request->user_apellido,
            'user_email' => $request->user_email,
            'user_password' => Hash::make($request->user_password),
            'user_telefono' => $request->user_telefono,
            'user_estado' => true,
        ]);

        return redirect('/register')->with('success', 'Usuario registrado exitosamente.');
    }
}
