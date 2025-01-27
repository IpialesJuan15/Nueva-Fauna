<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EspecieController;
use App\Http\Controllers\RevisionController;
use App\Models\Especie;

// Rutas públicas
Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/home', function () {
    return view('home');
})->name('home');

// Ruta para la vista de registro de usuarios
Route::get('/register', [UserController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [UserController::class, 'register']);


// Rutas de autenticación
Route::get('/login', function () {
    return view('login');
})->name('login');

Route::post('/login', [UserController::class, 'login']);
Route::post('/logout', [UserController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {

    Route::get('/FormInvest', [EspecieController::class, 'create'])->name('FormInvest');

    Route::get('/observador', function () {
        $especies = Especie::with(['imagenes', 'ubicaciones', 'genero.familia.reino'])
            ->whereHas('registros', function ($query) {
                $query->where('regis_estado', 'Aprobado'); // Filtrar por especies aprobadas
            })
            ->get();
    
        return view('observador', compact('especies'));
    })->name('observador');

    
    /*Route::get('/observador', function () {
        return view('observador');
    })->name('observador');*/

    Route::get('/taxonomo', function () {
        $especies = Especie::with(['imagenes', 'ubicaciones', 'genero.familia.reino'])->get();
        return view('taxonomo', compact('especies'));
    })->name('taxonomo');
});

Route::middleware(['auth'])->group(function () {
    // Rutas para las especies
    Route::prefix('especies')->group(function () {
        Route::get('/', [EspecieController::class, 'index'])->name('especies.index');
        Route::post('/', [EspecieController::class, 'store'])->name('especies.store');
        Route::put('/{id}', [EspecieController::class, 'update'])->name('especies.update');
        //Route::delete('/{id}', [EspecieController::class, 'destroy'])->name('especies.destroy');
        

        Route::post('/buscar', [EspecieController::class, 'search'])->name('especies.search');
        //Route::post('/{id}/validar', [EspecieController::class, 'validarEspecie'])->name('especies.validar');
        Route::post('/{id}/imagen', [EspecieController::class, 'actualizarImagen'])->name('especies.actualizarImagen');
        Route::get('/create', [EspecieController::class, 'create'])->name('especies.create');
        Route::get('/familias-generos', [EspecieController::class, 'getFamiliasGeneros'])->name('getFamiliasGeneros');
    });
    
    //Route::get('/report', [EspecieController::class, 'mostrarReporte'])->name('report');



});
Route::delete('/especies/{id}', [EspecieController::class, 'destroy'])->name('especies.destroy');

Route::post('/especies/{id}/validar', [EspecieController::class, 'validarEspecie'])->name('especies.validar');
Route::get('/observador/especies', [EspecieController::class, 'obtenerEspeciesAprobadas'])->name('observador.especies');
//Route::get('/observador/especies/{id}', [EspecieController::class, 'obtenerDetalleEspecie'])->name('observador.especie.detalle');

//Route::get('/especies-aprobadas', [EspecieController::class, 'obtenerEspeciesAprobadas'])->name('especies.aprobadas');

//Route::get('/observador', [EspecieController::class, 'vistaObservador'])->name('observador');
Route::get('/report', [EspecieController::class, 'mostrarReporte'])->name('reporte');

Route::get('/especies/validadas/contar', [EspecieController::class, 'contarEspeciesValidadas'])->name('especies.validadas.contar');
Route::get('/observaciones/validadas/contar', [EspecieController::class, 'contarObservacionesValidadas'])->name('observaciones.validadas.contar');


Route::get('/recuperarEmail', function () {
    return view('recuperarEmail');
})->name('recuperarEmail');


