<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EspecieController;
use App\Http\Controllers\RevisionController;

// Rutas públicas
Route::get('/', function () {
    return "HOLA MUNDO";
});

// Rutas de autenticación
Route::get('/login', function () {
    return view('login');
})->name('login');

Route::post('/login', [UserController::class, 'login']);
Route::post('/logout', [UserController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/FormInvest', function () {
        return view('FormInvest');
    })->name('FormInvest');

    Route::get('/observador', function () {
        return view('observador');
    })->name('observador');

    Route::get('/taxonomo', function () {
        return view('taxonomo');
    })->name('taxonomo');
});


// Rutas relacionadas con las especies
Route::middleware(['auth'])->group(function () {
    Route::post('/especies', [EspecieController::class, 'store'])->name('especies.store');
    Route::put('/especies/editar', [EspecieController::class, 'update'])->name('especies.update');
    Route::post('/especies/buscar', [EspecieController::class, 'search'])->name('especies.search');
    Route::delete('/especies/{id}', [EspecieController::class, 'destroy'])->name('especies.destroy');
    Route::get('/especies', [EspecieController::class, 'index'])->name('especies.index');
});



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
Route::post('/register', [UserController::class, 'register']);

Route::get('/recuperarEmail', function () {
    return view('recuperarEmail');
})->name('recuperarEmail');

// Rutas protegidas por autenticación


// Rutas de especies
Route::controller(EspecieController::class)->group(function () {
    Route::get('/especies', 'index')->name('especies.index');
    Route::post('/especies', 'store')->name('especies.store');
    Route::put('/especies/editar', 'update')->name('especies.update');
    Route::post('/especies/buscar', 'search')->name('especies.search');
    Route::delete('/especies/{id}', 'destroy')->name('especies.destroy');
});

// Rutas de revisiones
Route::post('/especies/{id}/revision', [RevisionController::class, 'store']);


// Rutas específicas para taxónomos
//Route::get('/taxonomo', [RevisionController::class, 'indexTaxonomo'])->name('taxonomo.index');
Route::post('/revisiones/{id}/actualizar', [RevisionController::class, 'actualizarEstado']);
Route::get('/revisiones/pendientes/count', [RevisionController::class, 'contarPendientes']);
Route::post('/taxonomo/filtrar', [RevisionController::class, 'filtrar']);

Route::get('/perfil', function () {
    return view('perfil');
})->name('perfil');
