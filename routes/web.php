<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EspecieController;


Route::get('/login', function () {
    return view('login');
})->name('login');

//Route::post('/login', [AuthController::class, 'login'])->name('login.process');
//Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


// Redirección según el rol
Route::get('/observador', function () {
    return view('observador');
});

Route::get('/FormInvest', function () {
    return view('FormInvest');
});



Route::post('/especies', [EspecieController::class, 'store'])->name('especies.store');
Route::put('/especies/editar', [EspecieController::class, 'update'])->name('especies.update');
Route::post('/especies/buscar', [EspecieController::class, 'search'])->name('especies.search');
Route::get('/especies', [EspecieController::class, 'index'])->name('especies.index');
Route::delete('/especies/{id}', [EspecieController::class, 'destroy'])->name('especies.destroy');


Route::get('/taxonomo', function () {
    return view('taxonomo');
});

Route::get('/home', function () {
    return view('home');
});

Route::get('/report', function () {
    return view('report');
});
<<<<<<< HEAD
Route::get('/observador', function () {
    return view('observador'); // Asegúrate de que el archivo observador.blade.php exista en resources/views
=======





Route::get('/register', function () {
    return view('register');
});

Route::post('/register', [UserController::class, 'register']);


Route::get('/recuperarEmail', function () {
    return view('recuperarEmail');
>>>>>>> 3980256b1330902501c83864fd5c74a7918b9968
});

// Ruta para la página Report
Route::get('/report', function () {
    return view('report'); // Esto carga el archivo resources/views/report.blade.php
});

<<<<<<< HEAD
Route::get('/taxonomo', function () {
    return view('taxonomo');
})->name('taxonomo');
=======
// Ruta principal de prueba
Route::get('/', function () {
    return "HOLA MUNDO"; // Esto carga el archivo resources/views/welcome.blade.php
});
>>>>>>> 3980256b1330902501c83864fd5c74a7918b9968

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