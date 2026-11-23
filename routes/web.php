<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductoController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:10,1');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::redirect('/home', '/productos')->name('home');

    // Admin y empleado: solo consulta
    Route::middleware('role:admin,empleado')
        ->prefix('productos')->name('productos.')
        ->group(function () {
            Route::get('/', [ProductoController::class, 'index'])->name('index');
        });

    // Solo admin: registrar, actualizar y eliminar
    Route::middleware('role:admin')
        ->prefix('productos')->name('productos.')
        ->where(['producto' => '[0-9]+'])
        ->group(function () {
            Route::get('/crear', [ProductoController::class, 'create'])->name('create');
            Route::post('/', [ProductoController::class, 'store'])->name('store');
            Route::get('/{producto}/editar', [ProductoController::class, 'edit'])->name('edit');
            Route::put('/{producto}', [ProductoController::class, 'update'])->name('update');
            Route::delete('/{producto}', [ProductoController::class, 'destroy'])->name('destroy');
        });
});