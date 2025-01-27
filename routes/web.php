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
Route::get('/register', function () {
    return view('register');
})->name('register');
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
        // Traer todas las especies, incluidas las creadas por el investigador.
        $especies = Especie::with(['imagenes', 'ubicaciones', 'genero.familia.reino'])
            ->get();  // Sin filtrar por esp_estado_valid (se pueden agregar filtros si es necesario)
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

});
Route::delete('/especies/{id}', [EspecieController::class, 'destroy'])->name('especies.destroy');

Route::post('/especies/{id}/validar', [EspecieController::class, 'validarEspecie'])->name('especies.validar');
Route::get('/observador/especies', [EspecieController::class, 'obtenerEspeciesAprobadas'])->name('observador.especies');
Route::get('/observador/especies/{id}', [EspecieController::class, 'obtenerDetalleEspecie'])
    ->name('observador.especie.detalle');

    //Route::get('/observador', [EspecieController::class, 'vistaObservador'])->name('observador');

// Ruta para la vista de report
Route::get('/report', function () {
    return view('report');
})->name('report');


Route::get('/recuperarEmail', function () {
    return view('recuperarEmail');
})->name('recuperarEmail');


// Rutas de especies
//Route::controller(EspecieController::class)->group(function () {
    //Route::get('/especies', 'index')->name('especies.index');
    //Route::post('/especies', 'store')->name('especies.store');
    //Route::put('/especies/editar', 'update')->name('especies.update');
    //Route::post('/especies/buscar', 'search')->name('especies.search');
  //  Route::delete('/especies/{id}', 'destroy')->name('especies.destroy');
//});

// Rutas de revisiones
//Route::post('/especies/{id}/revision', [RevisionController::class, 'store']);


// Rutas específicas para taxónomos
//Route::get('/taxonomo', [RevisionController::class, 'indexTaxonomo'])->name('taxonomo.index');
//Route::post('/revisiones/{id}/actualizar', [RevisionController::class, 'actualizarEstado']);
//Route::get('/revisiones/pendientes/count', [RevisionController::class, 'contarPendientes']);
//Route::post('/taxonomo/filtrar', [RevisionController::class, 'filtrar']);

//Route::get('/perfil', function () {
  //  return view('perfil');
//})->name('perfil');
