<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PasswordUpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_password_puede_ser_actualizado(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->from('/profile')
            ->put('/password', [
                'current_password'      => 'password',
                'password'              => 'NuevaPassword123!',
                'password_confirmation' => 'NuevaPassword123!',
            ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect('/profile');
    }

    public function test_password_actual_debe_ser_correcto_para_actualizarse(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->from('/profile')
            ->put('/password', [
                'current_password'      => 'contrasena-incorrecta',
                'password'              => 'NuevaPassword123!',
                'password_confirmation' => 'NuevaPassword123!',
            ]);

        $response->assertSessionHasErrors('current_password');
        $response->assertRedirect('/profile');
    }
}
