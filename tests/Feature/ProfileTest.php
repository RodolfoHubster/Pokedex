<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_pagina_de_perfil_se_muestra_correctamente(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/profile');

        $response->assertStatus(200);
    }

    public function test_informacion_de_perfil_puede_ser_actualizada(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->patch('/profile', [
                'name'  => 'Nombre Actualizado',
                'email' => 'nuevo@ejemplo.com',
            ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect('/profile');

        $user->refresh();

        $this->assertSame('Nombre Actualizado', $user->name);
        $this->assertSame('nuevo@ejemplo.com', $user->email);
        $this->assertNull($user->email_verified_at);
    }

    public function test_estado_de_verificacion_no_cambia_si_email_es_igual(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->patch('/profile', [
                'name'  => $user->name,
                'email' => $user->email,
            ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect('/profile');
        $this->assertNotNull($user->refresh()->email_verified_at);
    }

    public function test_usuario_puede_eliminar_su_cuenta(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->delete('/profile', [
                'password' => 'password',
            ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect('/');
        $this->assertGuest();
        $this->assertNull($user->fresh());
    }

    public function test_password_correcto_es_requerido_para_eliminar_cuenta(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->delete('/profile', [
                'password' => 'contrasena-incorrecta',
            ]);

        $response->assertSessionHasErrors('password');
        $this->assertNotNull($user->fresh());
    }
}
