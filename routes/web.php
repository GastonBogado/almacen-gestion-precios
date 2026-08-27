<?php

use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductoController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\ProductoProveedorController;
Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth','can:admin'])->group(function () {
    Route::post('/categorias', [CategoriaController::class, 'store'])->name('categorias.store');
    Route::delete('/categorias/{id}', [CategoriaController::class, 'delete'])->name('categorias.delete');
    Route::delete('/productos/{id}', [ProductoController::class, 'delete'])->name('productos.delete');
    Route::post('/productos', [ProductoController::class, 'store'])->name('productos.store');
    Route::post('/proveedores', [ProveedorController::class, 'store'])->name('proveedores.store');
    Route::get('/productos/{id}/edit', [ProductoController::class, 'edit'])->name('productos.edit');
    Route::put('/productos/{id}', [ProductoController::class, 'update'])->name('productos.update');
    Route::put('/producto-proveedores/{id}', [ProductoProveedorController::class, 'update'])->name('producto-proveedores.update');
    Route::post('/productos/{id}/proveedores', [ProductoProveedorController::class, 'store'])->name('producto-proveedores.store');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');;
    Route::post('/logout',[LoginController::class,'logout'])->name('logout');
});


Route::middleware(['guest'])->group(function () {
    Route::get('/login',[LoginController::class,'showLoginForm'])->name('login');
    Route::post('/login',[LoginController::class,'login'])->name('login.attempt')->middleware('throttle:5,1');
});

