<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Producto;
use App\Models\Categoria;
use App\Models\Marca;

class ProductoControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_obtener_lista_completa_de_productos()
    {
        $categoria = Categoria::create(['nombre' => 'Frenos']);
        $marca = Marca::create(['nombre' => 'Brembo']);

        Producto::create([
            'nombre' => 'Disco de Freno Test',
            'descripcion' => 'Descripción de prueba',
            'precio' => 150.50,
            'stock' => 10,
            'categoria_id' => $categoria->id,
            'marca_id' => $marca->id,
            'destacado' => false
        ]);

        $response = $this->getJson('/api/productos');

        $response->assertStatus(200)
            ->assertJsonCount(1);
    }

    public function test_devuelve_404_al_buscar_producto_inexistente()
    {
        $response = $this->getJson('/api/productos/999');

        $response->assertStatus(404)
            ->assertJson(['message' => 'Producto no encontrado']);
    }
}
