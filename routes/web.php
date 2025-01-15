<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\AuthController;

Route::get('/login', function () {
    return view('login');
})->name('login');

//Route::post('/login', [AuthController::class, 'login'])->name('login.process');
//Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


// Redirección según el rol
Route::get('/observador', function () {
    return view('observador');
})->name('observador')->middleware('auth');

Route::get('/FormInvest', function () {
    return view('FormInvest');
})->name('FormInvest')->middleware('auth');

Route::get('/taxonomo', function () {
    return view('taxonomo');
})->name('taxonomo')->middleware('auth');

Route::get('/home', function () {
    return view('home');
})->name('home')->middleware('auth');

Route::get('/report', function () {
    return view('report');
});





Route::get('/register', function () {
    return view('register');
});

//Route::post('/register', [UsuarioController::class, 'registrar']);


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
