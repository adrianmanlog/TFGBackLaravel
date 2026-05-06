<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_usuario_puede_registrarse_con_datos_validos()
    {
        $datos = [
            'nombre' => 'Usuario Test',
            'email' => 'test@ballestasbeni.com',
            'password' => '12345678'
        ];

        $response = $this->postJson('/api/registro', $datos);

        $response->assertStatus(201)
            ->assertJsonStructure(['message', 'access_token', 'user']);

        $this->assertDatabaseHas('usuarios', ['email' => 'test@ballestasbeni.com']);
    }

    public function test_error_al_registrar_con_email_duplicado()
    {
        User::create([
            'nombre' => 'Existente',
            'email' => 'duplicado@ballestasbeni.com',
            'password' => bcrypt('123456'),
            'es_admin' => false
        ]);

        $response = $this->postJson('/api/registro', [
            'nombre' => 'Nuevo Intento',
            'email' => 'duplicado@ballestasbeni.com',
            'password' => '12345678'
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    public function test_login_exitoso_devuelve_token()
    {
        User::create([
            'nombre' => 'Admin Test',
            'email' => 'admin_test@ballestasbeni.com',
            'password' => bcrypt('admin123'),
            'es_admin' => true
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'admin_test@ballestasbeni.com',
            'password' => 'admin123'
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['message', 'access_token', 'es_admin']);
    }
}
