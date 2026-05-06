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
        $request->validate([
            'usuario_id' => 'required|integer',
            'total' => 'required|numeric',
            'items' => 'required|array'
        ]);

        try {
            DB::beginTransaction();

            sleep(2);
            $pagoAceptado = true;

            if (!$pagoAceptado) {
                throw new \Exception("Pago rechazado por el banco.");
            }

            $pedido = Pedido::create([
                'usuario_id' => $request->usuario_id,
                'total' => $request->total,
                'estado' => 'Pagado'
            ]);

            foreach ($request->items as $item) {
                LineaPedido::create([
                    'pedido_id' => $pedido->id,
                    'producto_id' => $item['producto']['id'],
                    'cantidad' => $item['cantidad'],
                    'precio_unitario' => $item['producto']['precio']
                ]);

                $productoReal = Producto::find($item['producto']['id']);
                if ($productoReal) {
                    $productoReal->stock = $productoReal->stock - $item['cantidad'];
                    $productoReal->save();
                }
            }

            DB::commit();

            return response()->json([
                'message' => '¡Pago completado y pedido registrado!',
                'pedido_id' => $pedido->id
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error al procesar el pedido: ' . $e->getMessage()], 500);
        }
    }

    public function misPedidos($usuario_id)
    {
        $pedidos = Pedido::with('lineas.producto')
            ->where('usuario_id', $usuario_id)
            ->orderBy('fecha_pedido', 'desc')
            ->get();

        return response()->json($pedidos, 200);
    }

    public function descargarFactura($id)
    {
        $pedido = Pedido::with('lineas.producto')->find($id);

        if (!$pedido) {
            return response()->json(['error' => 'Pedido no encontrado'], 404);
        }

        $pdf = Pdf::loadView('factura', compact('pedido'));

        return $pdf->download('Factura_BallestasBeni_000' . $pedido->id . '.pdf');
    }
    public function index()
    {
        $pedidos = Pedido::with(['lineas.producto', 'usuario'])
            ->orderBy('fecha_pedido', 'desc')
            ->get();

        return response()->json($pedidos, 200);
    }
}
