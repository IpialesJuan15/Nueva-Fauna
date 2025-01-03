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

// Ruta principal de prueba
Route::get('/', function () {
    return view('welcome'); // Esto carga el archivo resources/views/welcome.blade.php
});

Route::get('/taxonomo', function () {
    return view('taxonomo');
})->name('taxonomo');

Route::get('/home', function () {
    return view('home');
});
