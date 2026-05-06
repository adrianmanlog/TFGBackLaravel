<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductoController;
use App\Http\Controllers\Api\CategoriaController;
use App\Http\Controllers\Api\MarcaController;
use App\Http\Controllers\Api\MensajeContactoController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PedidoController;


Route::get('/categorias', [CategoriaController::class, 'index']);
Route::post('/categorias', [CategoriaController::class, 'store']); // <-- NUEVA

Route::post('/procesar-pago', [PedidoController::class, 'procesarPago']);
Route::get('/marcas', [MarcaController::class, 'index']);
Route::post('/marcas', [MarcaController::class, 'store']); // <-- NUEVA
Route::get('/usuarios/{id}/pedidos', [PedidoController::class, 'misPedidos']);
Route::get('/pedidos/{id}/factura', [PedidoController::class, 'descargarFactura']);
Route::get('/pedidos', [PedidoController::class, 'index']);

Route::get('/productos', [ProductoController::class, 'index']);
Route::get('/productos/destacados', [ProductoController::class, 'destacados']);
Route::get('/productos/{id}', [ProductoController::class, 'show']);
Route::post('/productos', [ProductoController::class, 'store']);
Route::put('/productos/{id}', [ProductoController::class, 'update']);
Route::delete('/productos/{id}', [ProductoController::class, 'destroy']);


Route::post('/contacto', [MensajeContactoController::class, 'store']);
Route::get('/contacto', [MensajeContactoController::class, 'index']);

Route::post('/registro', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
});
