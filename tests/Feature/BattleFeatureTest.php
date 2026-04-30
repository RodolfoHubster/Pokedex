<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Http;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BattleFeatureTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Battle válido responde 200 y muestra al ganador.
     * Técnica única: usa fake diferente para A (pikachu) y B (bulbasaur)
     * en el mismo test — dos respuestas distintas.
     */
    public function test_valid_battle_returns_200_and_shows_winner(): void
    {
        // Autor: Andrehi Sandoval
        Http::fake([
            // Pikachu: hp=35, atk=55, def=40  → score=130
            'https://pokeapi.co/api/v2/pokemon/pikachu' => Http::response(
                $this->pikachuData(), 200
            ),
            // Machamp: hp=90, atk=130, def=80 → score=300
            'https://pokeapi.co/api/v2/pokemon/machamp' => Http::response(
                $this->machampData(), 200
            ),
        ]);

        $response = $this->get('/battle?A=pikachu&B=machamp');

        $response->assertStatus(200);
        // machamp gana (score 300 > 130) → ganador B
        $response->assertSee('machamp');
    }

    /** Battle con Pokémon inválido responde de forma controlada (vista amigable). */
    public function test_battle_with_invalid_pokemon_shows_error(): void
    {
        // Autor: Rodolfo Huitron
        Http::fake([
            'https://pokeapi.co/api/v2/pokemon/pikachu'       => Http::response($this->pikachuData(), 200),
            'https://pokeapi.co/api/v2/pokemon/pokemoninvalido' => Http::response([], 404),
        ]);

        $response = $this->get('/battle?A=pikachu&B=pokemoninvalido');

        $response->assertStatus(200);
        $response->assertSee('pokemoninvalido');
    }

    // ──────────────────────────────────────────────
    //  Helpers
    // ──────────────────────────────────────────────

    private function pikachuData(): array
    {
        return [
            'name'    => 'pikachu',
            'sprites' => ['front_default' => 'https://example.com/pikachu.png'],
            'types'   => [['type' => ['name' => 'electric']]],
            'stats'   => [
                ['base_stat' => 35],
                ['base_stat' => 55],
                ['base_stat' => 40],
            ],
        ];
    }

    private function machampData(): array
    {
        return [
            'name'    => 'machamp',
            'sprites' => ['front_default' => 'https://example.com/machamp.png'],
            'types'   => [['type' => ['name' => 'fighting']]],
            'stats'   => [
                ['base_stat' => 90],
                ['base_stat' => 130],
                ['base_stat' => 80],
            ],
        ];
    }
}
