<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'user_email' => 'required|email',
            'password' => 'required', // Debe coincidir con el nombre del campo en el formulario
        ]);

        $credentials = $request->only('user_email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

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
}
