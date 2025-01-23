<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Registro de usuario
     */
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

        // Crear usuario
        Usuario::create([
            'tipus_id' => $request->tipus_id,
            'user_cedula' => $request->user_cedula,
            'user_nombre' => $request->user_nombre,
            'user_apellido' => $request->user_apellido,
            'user_email' => $request->user_email,
            'user_password' => Hash::make($request->user_password), // Encripta la contraseña
            'user_telefono' => $request->user_telefono,
            'user_estado' => true,
        ]);

        return redirect('/home')->with('success', 'Usuario registrado con éxito');
    }

    /**
     * Inicio de sesión
     */
    public function login(Request $request)
    {
        // Validación
        $request->validate([
            'user_email' => 'required|email',
            'user_password' => 'required|string',
        ]);

        // Mapeo de claves para Auth::attempt
        $credentials = [
            'user_email' => $request->user_email,
            'password' => $request->user_password, // Cambiar 'password' por 'user_password'
        ];

        // Autenticación
        if (Auth::attempt($credentials)) {
            // Regenerar sesión para prevenir fijación de sesión
            $request->session()->regenerate();

            // Obtener usuario autenticado
            $user = Auth::user();

            // Redirección según el tipo de usuario
            switch ($user->tipoUsuario->tipus_detalles) {
                case 'Observador':
                    return redirect()->route('observador');
                case 'Taxonomo':
                    return redirect()->route('taxonomo');
                case 'Investigador':
                    return redirect()->route('FormInvest');
                default:
                    return redirect('/home');
            }
        }

        // Si las credenciales son incorrectas
        return back()->withErrors([
            'error' => 'Las credenciales proporcionadas no coinciden con nuestros registros.',
        ]);
    }


    /**
     * Cierre de sesión
     */
    public function logout(Request $request)
    {
        // Cierra la sesión del usuario autenticado
        Auth::logout();

        // Invalida la sesión actual
        $request->session()->invalidate();

        // Genera un nuevo token CSRF
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'Sesión cerrada correctamente.');
    }
}
