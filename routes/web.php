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

// Rutas de registro y recuperación
Route::get('/register', function () {
    return view('register');
})->name('register');
Route::post('/register', [UserController::class, 'register']);

Route::get('/recuperarEmail', function () {
    return view('recuperarEmail');
})->name('recuperarEmail');

// Rutas protegidas por autenticación
Route::middleware(['auth'])->group(function () {
    // Rutas comunes
    Route::get('/home', function () {
        return view('home');
    })->name('home');

    Route::get('/registros', function () {
        return view('registros');
    })->name('registros');

    Route::get('/report', function () {
        return view('report');
    })->name('report');

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

    // Rutas específicas por rol
    Route::get('/observador', function () {
        return view('observador');
    })->middleware('role:observador');

    Route::get('/FormInvest', function () {
        return view('FormInvest');
    })->middleware('role:investigador');

    // Rutas específicas para taxónomos
    Route::middleware(['role:taxonomo'])->group(function () {
        Route::get('/taxonomo', [RevisionController::class, 'indexTaxonomo'])->name('taxonomo.index');
        Route::post('/revisiones/{id}/actualizar', [RevisionController::class, 'actualizarEstado'])->name('revision.actualizar');
        Route::get('/revisiones/pendientes/count', [RevisionController::class, 'contarPendientes'])->name('revision.contar');
        Route::post('/taxonomo/filtrar', [RevisionController::class, 'filtrar'])->name('revision.filtrar');
    });

    // Rutas de perfil de usuario (si las necesitas)
    Route::get('/perfil', function () {
        return view('perfil');
    })->name('perfil');
    
});

// Middleware para verificar roles
Route::middleware(['auth', 'role:admin'])->group(function () {
    // Aquí puedes agregar rutas específicas para administradores si las necesitas
});