<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pedido;
use App\Models\LineaPedido;
use App\Models\Producto;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class PedidoController extends Controller
{
    public function procesarPago(Request $request)
    {
        // 1. Validar que nos llegan los datos básicos
        $request->validate([
            'usuario_id' => 'required|integer',
            'total' => 'required|numeric',
            'items' => 'required|array'
        ]);

        try {
            // Iniciamos la transacción segura
            DB::beginTransaction();

            // ==========================================
            // SIMULACIÓN DE PASARELA DE PAGO FALSA
            // ==========================================
            // Hacemos que el servidor "piense" 2 segundos como si hablara con VISA/Mastercard
            sleep(2); 
            // Podrías poner un random aquí para simular tarjetas rechazadas, pero para el TFG lo dejamos en 100% éxito.
            $pagoAceptado = true;

            if (!$pagoAceptado) {
                throw new \Exception("Pago rechazado por el banco.");
            }

            // 2. Crear el registro del Pedido Principal
            $pedido = Pedido::create([
                'usuario_id' => $request->usuario_id,
                'total' => $request->total,
                'estado' => 'Pagado' // Automáticamente pagado
            ]);

            // 3. Crear las líneas y restar el stock
            foreach ($request->items as $item) {
                // Guardamos la línea de la factura
                LineaPedido::create([
                    'pedido_id' => $pedido->id,
                    'producto_id' => $item['producto']['id'],
                    'cantidad' => $item['cantidad'],
                    'precio_unitario' => $item['producto']['precio'] // Guardamos el precio actual por si en el futuro cambia
                ]);

                // Restamos el stock del producto real
                $productoReal = Producto::find($item['producto']['id']);
                if ($productoReal) {
                    $productoReal->stock = $productoReal->stock - $item['cantidad'];
                    $productoReal->save();
                }
            }

            // Si llegamos aquí sin errores, confirmamos la inserción en Base de Datos
            DB::commit();

            return response()->json([
                'message' => '¡Pago completado y pedido registrado!',
                'pedido_id' => $pedido->id
            ], 201);

        } catch (\Exception $e) {
            // Si algo explota, deshacemos todo lo de la BD
            DB::rollBack();
            return response()->json(['error' => 'Error al procesar el pedido: ' . $e->getMessage()], 500);
        }
    }

    public function misPedidos($usuario_id)
    {
        // Buscamos los pedidos del usuario y nos traemos las líneas y los productos
        $pedidos = Pedido::with('lineas.producto')
            ->where('usuario_id', $usuario_id)
            ->orderBy('fecha_pedido', 'desc') // Los más recientes primero
            ->get();

        return response()->json($pedidos, 200);
    }

    public function descargarFactura($id)
    {
        // Buscamos el pedido con sus líneas y productos
        $pedido = Pedido::with('lineas.producto')->find($id);

        if (!$pedido) {
            return response()->json(['error' => 'Pedido no encontrado'], 404);
        }

        // Cargamos la vista HTML que creamos y le pasamos los datos del pedido
        $pdf = Pdf::loadView('factura', compact('pedido'));

        // Devolvemos el archivo para que el navegador lo descargue
        return $pdf->download('Factura_BallestasBeni_000' . $pedido->id . '.pdf');
    }
    // Obtener TODOS los pedidos para el panel de Admin
    public function index()
    {
        $pedidos = Pedido::with(['lineas.producto', 'usuario'])
            ->orderBy('fecha_pedido', 'desc')
            ->get();

        return response()->json($pedidos, 200);
    }
}