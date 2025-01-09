<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;

Route::get('/report', function () {
    return view('report');
});


Route::get('/observador', function () {
    return view('observador'); // Asegúrate de que el archivo observador.blade.php exista en resources/views
});

Route::get('/FormInvest', function () {
    return view('FormInvest');
});

Route::get('/login', function () {
    return view('login');
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

Route::get('/taxonomo', function () {
    return view('taxonomo');
})->name('taxonomo');

Route::get('/home', function () {
    return view('home');
});
