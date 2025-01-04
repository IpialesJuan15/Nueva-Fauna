<?php

use Illuminate\Support\Facades\Route;

Route::get('/report', function () {
    return view('report');
});
Route::get('/observador', function () {
    return view('observador'); // Asegúrate de que el archivo observador.blade.php exista en resources/views
});

// Ruta para la página Report
Route::get('/report', function () {
    return view('report'); // Esto carga el archivo resources/views/report.blade.php
});

Route::get('/taxonomo', function () {
    return view('taxonomo');
})->name('taxonomo');

Route::get('/home', function () {
    return view('home');
});
Route::get('/login', function () {
    return view('login'); // Muestra login.blade.php
})->name('login');

// Ruta para registro de usuarios
Route::get('/register', function () {
    return view('register'); // Muestra register.blade.php
})->name('register');

// Ruta para la página principal
Route::get('/home', function () {
    return view('home'); // Muestra home.blade.php
})->name('home');
Route::get('/recuperarEmail', function () {
    return view('recuperarEmail');
})->name('recuperarEmail');
Route::get('/registros', function () {
    return view('registros'); // Cambia "registros" al nombre correcto del archivo Blade.
})->name('registros');

Route::post('/logout', function () {
    // Implementa la lógica de cierre de sesión aquí.
    // Por ejemplo: Auth::logout(); return redirect('/');
})->name('logout');