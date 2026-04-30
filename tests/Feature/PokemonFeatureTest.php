<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Http;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PokemonFeatureTest extends TestCase
{
    use RefreshDatabase;

    // ──────────────────────────────────────────────
    //  Autor: Rodolfo Huitron
    // ──────────────────────────────────────────────

    /** GET / responde 200 (Home). */
    public function test_home_returns_200(): void
    {
        // Autor: Rodolfo Huitron
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    /** GET /pokemon responde 200 (Listado). */
    public function test_pokemon_list_returns_200(): void
    {
        // Autor: Rodolfo Huitron
        Http::fake([
            'https://pokeapi.co/api/v2/pokemon?limit=20' => Http::response([
                'results' => [
                    ['name' => 'bulbasaur', 'url' => 'https://pokeapi.co/api/v2/pokemon/1/'],
                ],
            ], 200),
            'https://pokeapi.co/api/v2/pokemon/bulbasaur' => Http::response(
                $this->bulbasaurData(), 200
            ),
        ]);

        $response = $this->get('/pokemon');

        $response->assertStatus(200);
    }

    /** GET /pokemon/nombreinvalido responde de forma controlada (vista amigable). */
    public function test_invalid_pokemon_shows_error_view(): void
    {
        // Autor: Rodolfo Huitron
        Http::fake([
            'https://pokeapi.co/api/v2/pokemon/nombreinvalido' => Http::response([], 404),
        ]);

        $response = $this->get('/pokemon/nombreinvalido');

        $response->assertStatus(200);
        $response->assertSee('nombreinvalido');
    }

    /** Buscador vacío en /pokemon muestra validación (mensaje de error). */
    public function test_empty_search_shows_validation_error(): void
    {
        // Autor: Rodolfo Huitron (colaboración con Sandoval en vista)
        $response = $this->get('/pokemon?search=');

        $response->assertStatus(200);
        $response->assertSee('no puede estar vacío');
    }

    // ──────────────────────────────────────────────
    //  Autor: Andrehi Sandoval
    // ──────────────────────────────────────────────

    /** GET /about responde 200 (Acerca de). */
    public function test_about_returns_200(): void
    {
        // Autor: Andrehi Sandoval
        $response = $this->get('/about');

        $response->assertStatus(200);
    }

    /** GET /pokemon/pikachu (nombre válido) responde 200. */
    public function test_valid_pokemon_show_returns_200(): void
    {
        // Autor: Andrehi Sandoval — usa Http::fake para evitar dependencia de internet
        Http::fake([
            'https://pokeapi.co/api/v2/pokemon/pikachu' => Http::response(
                $this->pikachuData(), 200
            ),
        ]);

        $response = $this->get('/pokemon/pikachu');

        $response->assertStatus(200);
        $response->assertSee('pikachu');
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

    private function bulbasaurData(): array
    {
        return [
            'name'    => 'bulbasaur',
            'sprites' => ['front_default' => 'https://example.com/bulbasaur.png'],
            'types'   => [
                ['type' => ['name' => 'grass']],
                ['type' => ['name' => 'poison']],
            ],
            'stats' => [
                ['base_stat' => 45],
                ['base_stat' => 49],
                ['base_stat' => 49],
            ],
        ];
    }
}
