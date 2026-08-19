<?php

use Illuminate\Http\Request;
use App\Http\Controllers\GastoController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ActualizacionPrecioController;

Route::post('/actualizaciones-precio', [ActualizacionPrecioController::class, 'store']);
Route::post('/gastos', [GastoController::class, 'store']);