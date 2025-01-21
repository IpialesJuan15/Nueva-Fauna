<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    protected function redirectTo($request)
    {
        // Redirige al login si no está autenticado
        if (!$request->expectsJson()) {
            return route('login'); // Ruta del login
        }
    }
}
