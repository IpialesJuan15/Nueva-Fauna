<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;

use App\Http\Controllers\AuthController;


Route::get('/login', function () {
    return view('login');
});


Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.process');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


// Rutas protegidas según el rol
Route::middleware(['auth'])->group(function () {
    Route::get('/observador', function () {
        return view('observador');
    })->name('observador');

    Route::get('/FormInvest', function () {
        return view('FormInvest');
    })->name('FormInvest');

    Route::get('/taxonomo', function () {
        return view('taxonomo');
    })->name('taxonomo');
});

Route::get('/report', function () {
    return view('report');
});





Route::get('/register', function () {
    return view('register');
});

Route::post('/register', [UsuarioController::class, 'registrar']);


Route::get('/recuperarEmail', function () {
    return view('recuperarEmail');
});

// Ruta para la página Report
Route::get('/report', function () {
    return view('report'); // Esto carga el archivo resources/views/report.blade.php
});

// Ruta principal de prueba
Route::get('/', function () {
    return "HOLA MUNDO"; // Esto carga el archivo resources/views/welcome.blade.php
});

Route::get('/home', function () {
    return view('home');
});
