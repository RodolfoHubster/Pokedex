<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_pantalla_de_login_se_muestra_correctamente(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
    }

    public function test_usuarios_pueden_autenticarse_con_login(): void
    {
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email'    => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard', absolute: false));
    }

    public function test_usuarios_no_pueden_autenticarse_con_password_incorrecto(): void
    {
        $user = User::factory()->create();

        $this->post('/login', [
            'email'    => $user->email,
            'password' => 'contrasena-incorrecta',
        ]);

        $this->assertGuest();
    }

    public function test_usuarios_pueden_cerrar_sesion(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $this->assertGuest();
        $response->assertRedirect('/');
    }
}
