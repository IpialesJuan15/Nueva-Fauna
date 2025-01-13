<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Usuario;

class AuthController extends Controller
{
    // Método para mostrar el formulario de inicio de sesión
    public function showLoginForm()
    {
        return view('login');
    }

    // Método para procesar el inicio de sesión
    public function login(Request $request)
    {
        $request->validate([
            'user_email' => 'required|email',
            'user_password' => 'required',
        ]);

        // Buscar al usuario por email
        $usuario = Usuario::where('user_email', $request->user_email)->first();

        if ($usuario && password_verify($request->user_password, $usuario->user_password)) {
            // Guardamos en sesión el usuario autenticado
            session(['usuario' => $usuario]);

            // Redirigir según el tipo de usuario
            if ($usuario->tipus_id == 1) {
                return redirect()->route('observador');
            } elseif ($usuario->tipus_id == 2) {
                return redirect()->route('FormInvest');
            } elseif ($usuario->tipus_id == 3) {
                return redirect()->route('taxonomo');
            }
        }

        return back()->withErrors(['error' => 'Credenciales incorrectas.']);
    }

    // Método para cerrar sesión
    public function logout()
    {
        session()->forget('usuario');
        return redirect('/login');
    }
}
