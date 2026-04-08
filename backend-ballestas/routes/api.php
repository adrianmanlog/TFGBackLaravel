<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductoController;
use App\Http\Controllers\Api\CategoriaController;

/*
|--------------------------------------------------------------------------
| Rutas de la API (Backend Ballestas Beni)
|--------------------------------------------------------------------------
*/

// Rutas públicas (No requieren estar logueado)
Route::get('/categorias', [CategoriaController::class, 'index']);
Route::get('/productos', [ProductoController::class, 'index']);
Route::get('/productos/destacados', [ProductoController::class, 'destacados']);

// (Más adelante aquí pondremos las rutas protegidas con Sanctum para los Admins)