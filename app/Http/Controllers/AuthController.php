<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Usuario;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // Validar los datos
        $request->validate([
            'user_email' => 'required|email',
            'password' => 'required'
        ]);
    
        $credentials = $request->only('user_email', 'password'); // Asegúrate de que 'password' sea correcto
    
        if (Auth::attempt(['user_email' => $credentials['user_email'], 'password' => $credentials['password']])) {
            $user = Auth::user();
    
            // Redirigir según el tipo de usuario
            if ($user->tipus_id == 1) {
                return redirect('/observador');
            } elseif ($user->tipus_id == 2) {
                return redirect('/FormInvest');
            } elseif ($user->tipus_id == 3) {
                return redirect('/taxonomo');
            }
        }
    
        return back()->withErrors(['message' => 'Credenciales incorrectas']);
    }

    // Método para cerrar sesión

    public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }
}
