<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateUser
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Verifica si el usuario está autenticado en la sesión
        if (!session()->has('usuario')) {
            return redirect('/login')->withErrors(['error' => 'Debes iniciar sesión.']);
        }

        return $next($request);
    }
}
