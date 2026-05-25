<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductoController;
use App\Http\Controllers\Api\CategoriaController;
use App\Http\Controllers\Api\MarcaController;
use App\Http\Controllers\Api\MensajeContactoController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PedidoController;


Route::get('/categorias', [CategoriaController::class, 'index']);
Route::post('/categorias', [CategoriaController::class, 'store']);

Route::post('/procesar-pago', [PedidoController::class, 'procesarPago']);
Route::get('/marcas', [MarcaController::class, 'index']);
Route::post('/marcas', [MarcaController::class, 'store']);
Route::get('/usuarios/{id}/pedidos', [PedidoController::class, 'misPedidos']);
Route::get('/pedidos/{id}/factura', [PedidoController::class, 'descargarFactura']);
Route::get('/pedidos', [PedidoController::class, 'index']);
Route::post('/productos/importar', [ProductoController::class, 'importar']);
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

use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;

Route::post('/contacto', function (Request $request) {
    $request->validate([
        'nombre' => 'required|string|max:100',
        'telefono' => 'required|string|max:20',
        'mensaje' => 'required|string'
    ]);

    $contenido = "Has recibido un nuevo mensaje de contacto:\n\n" .
        "Nombre: " . $request->nombre . "\n" .
        "Teléfono: " . $request->telefono . "\n" .
        "Mensaje:\n" . $request->mensaje;

    Mail::raw($contenido, function ($message) {
        $message->to('adriangalileaa@gmail.com')
            ->subject('Nuevo mensaje desde la web de Ballestas Beni');
    });

    return response()->json(['message' => 'Correo enviado']);
});
