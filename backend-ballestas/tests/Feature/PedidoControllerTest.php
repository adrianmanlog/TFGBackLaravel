<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Producto;
use App\Models\Categoria;
use App\Models\Marca;

class PedidoControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_procesar_pago_crea_pedido_y_reduce_stock()
    {
        $user = User::create([
            'nombre' => 'Cliente Comprador',
            'email' => 'compras@test.com',
            'password' => bcrypt('123456'),
            'es_admin' => false
        ]);

        $categoria = Categoria::create(['nombre' => 'Suspensión']);
        $marca = Marca::create(['nombre' => 'Scania']);

        $producto = Producto::create([
            'nombre' => 'Amortiguador Test',
            'descripcion' => 'Test',
            'precio' => 100,
            'stock' => 5,
            'categoria_id' => $categoria->id,
            'marca_id' => $marca->id,
            'destacado' => false
        ]);

        $payload = [
            'usuario_id' => $user->id,
            'total' => 200,
            'items' => [
                [
                    'cantidad' => 2,
                    'producto' => [
                        'id' => $producto->id,
                        'precio' => 100
                    ]
                ]
            ]
        ];

        $response = $this->postJson('/api/pedidos/procesar', $payload);

        $response->assertStatus(201)
            ->assertJsonStructure(['message', 'pedido_id']);

        $this->assertDatabaseHas('pedidos', [
            'usuario_id' => $user->id,
            'total' => 200
        ]);

        $this->assertDatabaseHas('productos', [
            'id' => $producto->id,
            'stock' => 3
        ]);
    }
}
