<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_pantalla_de_registro_se_muestra_correctamente(): void
    {
        $response = $this->get('/register');
        $response->assertStatus(200);
    }

    public function test_nuevos_usuarios_pueden_registrarse(): void
    {
        $response = $this->post('/register', [
            'name'                  => 'Usuario Prueba',
            'email'                 => 'prueba@ejemplo.com',
            'password'              => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard', absolute: false));
    }
}
