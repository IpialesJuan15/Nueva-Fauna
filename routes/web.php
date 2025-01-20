<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EspecieController;

// Ruta para la vista de login
Route::get('/login', function () {
    return view('login');
})->name('login');

// Redirección según el rol
Route::get('/observador', function () {
    return view('observador');
});

// Ruta para formulario de investigación
Route::get('/FormInvest', function () {
    return view('FormInvest');
});

// Rutas relacionadas con las especies
Route::post('/especies', [EspecieController::class, 'store'])->name('especies.store');
Route::put('/especies/editar', [EspecieController::class, 'update'])->name('especies.update');
Route::post('/especies/buscar', [EspecieController::class, 'search'])->name('especies.search');
Route::get('/especies', [EspecieController::class, 'index'])->name('especies.index');
Route::delete('/especies/{id}', [EspecieController::class, 'destroy'])->name('especies.destroy');

// Ruta para la vista de taxónomo
Route::get('/taxonomo', function () {
    return view('taxonomo');
})->name('taxonomo');

// Ruta para la vista de home
Route::get('/home', function () {
    return view('home');
})->name('home');

// Ruta para la vista de report
Route::get('/report', function () {
    return view('report');
})->name('report');

// Ruta para la vista de registro de usuarios
Route::get('/register', function () {
    return view('register');
})->name('register');

// Ruta para procesar registro de usuarios
Route::post('/register', [UserController::class, 'register']);

// Ruta para la recuperación de contraseña
Route::get('/recuperarEmail', function () {
    return view('recuperarEmail'); // Cambia "recuperarEmail" al nombre correcto del archivo Blade.
})->name('recuperarEmail');

// Ruta para registros adicionales
Route::get('/registros', function () {
    return view('registros'); // Cambia "registros" al nombre correcto del archivo Blade.
})->name('registros');

// Ruta principal
Route::get('/', function () {
    return "HOLA MUNDO"; // Ruta principal de prueba
});

// Ruta de cierre de sesión
Route::post('/logout', function () {
    // Implementa la lógica de cierre de sesión aquí.
    // Por ejemplo: Auth::logout(); return redirect('/');
})->name('logout');
