<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Http;
use Tests\TestCase;

/**
 * 2 Feature extra — Misión Battle
 * Técnica única: fake diferente para A y B en el mismo test
 * Autores: Rodolfo Huitron / Andrehi Sandoval
 */
class BattleTest extends TestCase
{
    /** @test */
    // Autor: Rodolfo Huitron
    // Técnica única: dos fakes distintos en el mismo test (A=pikachu, B=bulbasaur)
    public function battle_valido_responde_200_y_muestra_ganador(): void
    {
        Http::fake([
            'https://pokeapi.co/api/v2/pokemon/pikachu' => Http::response([
                'name'  => 'pikachu',
                'stats' => [
                    ['base_stat' => 35, 'stat' => ['name' => 'hp']],
                    ['base_stat' => 55, 'stat' => ['name' => 'attack']],
                    ['base_stat' => 40, 'stat' => ['name' => 'defense']],
                ],
            ], 200),
            'https://pokeapi.co/api/v2/pokemon/bulbasaur' => Http::response([
                'name'  => 'bulbasaur',
                'stats' => [
                    ['base_stat' => 45, 'stat' => ['name' => 'hp']],
                    ['base_stat' => 49, 'stat' => ['name' => 'attack']],
                    ['base_stat' => 49, 'stat' => ['name' => 'defense']],
                ],
            ], 200),
        ]);

        $response = $this->get('/battle?A=pikachu&B=bulbasaur');

        $response->assertStatus(200);
        $response->assertSee('bulbasaur');
    }

    /** @test */
    // Autor: Andrehi Sandoval
    public function battle_con_pokemon_invalido_responde_controlado(): void
    {
        Http::fake([
            'https://pokeapi.co/api/v2/pokemon/pikachu' => Http::response([
                'name'  => 'pikachu',
                'stats' => [
                    ['base_stat' => 35, 'stat' => ['name' => 'hp']],
                    ['base_stat' => 55, 'stat' => ['name' => 'attack']],
                    ['base_stat' => 40, 'stat' => ['name' => 'defense']],
                ],
            ], 200),
            'https://pokeapi.co/api/v2/pokemon/missingno' => Http::response([], 404),
        ]);

        $response = $this->get('/battle?A=pikachu&B=missingno');

        // Respuesta controlada (no 500) y muestra el nombre del pokémon inválido
        $this->assertContains($response->status(), [200, 422]);
        $response->assertSee('missingno');
    }
}
