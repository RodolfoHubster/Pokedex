<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Http;
use Tests\TestCase;

/**
 * 6 Feature tests base — Rutas del proyecto
 * Autores: Rodolfo Huitron / Andrehi Sandoval
 */
class PokemonRoutesTest extends TestCase
{
    // ── Fake de la PokéAPI reutilizable ───────────────────────────────────────

    private function fakePikachu(): void
    {
        Http::fake([
            'https://pokeapi.co/api/v2/pokemon/pikachu' => Http::response([
                'name'    => 'pikachu',
                'sprites' => ['front_default' => 'https://example.com/pikachu.png'],
                'types'   => [['type' => ['name' => 'electric']]],
                'stats'   => [
                    ['base_stat' => 35, 'stat' => ['name' => 'hp']],
                    ['base_stat' => 55, 'stat' => ['name' => 'attack']],
                    ['base_stat' => 40, 'stat' => ['name' => 'defense']],
                ],
                'height' => 4,
                'weight' => 60,
                'abilities' => [
                    ['ability' => ['name' => 'static']],
                ],
            ], 200),
            'https://pokeapi.co/api/v2/pokemon?limit=20&offset=0' => Http::response([
                'results' => [
                    ['name' => 'pikachu', 'url' => 'https://pokeapi.co/api/v2/pokemon/25/'],
                ],
            ], 200),
            'https://pokeapi.co/api/v2/pokemon/25/' => Http::response([
                'name'    => 'pikachu',
                'sprites' => ['front_default' => 'https://example.com/pikachu.png'],
            ], 200),
            '*' => Http::response([], 200),
        ]);
    }

    // ── Tests ─────────────────────────────────────────────────────────────────

    /** @test */
    // Autor: Rodolfo Huitron
    public function get_home_responde_200(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    /** @test */
    // Autor: Rodolfo Huitron
    public function get_pokemon_listado_responde_200(): void
    {
        $this->fakePikachu();

        $response = $this->get('/pokemon');
        $response->assertStatus(200);
    }

    /** @test */
    // Autor: Rodolfo Huitron
    public function get_about_responde_200(): void
    {
        $response = $this->get('/about');
        $response->assertStatus(200);
    }

    /** @test */
    // Autor: Andrehi Sandoval
    public function get_pokemon_pikachu_nombre_valido_responde_200(): void
    {
        $this->fakePikachu();

        $response = $this->get('/pokemon/pikachu');
        $response->assertStatus(200);
    }

    /** @test */
    // Autor: Andrehi Sandoval
    public function get_pokemon_nombre_invalido_responde_de_forma_controlada(): void
    {
        Http::fake([
            'https://pokeapi.co/api/v2/pokemon/nombreinvalido999' => Http::response([], 404),
            '*' => Http::response([], 200),
        ]);

        $response = $this->get('/pokemon/nombreinvalido999');

        // Acepta 200 (vista amigable) o 404 — ambos son válidos según la rúbrica
        $this->assertContains($response->status(), [200, 404]);
    }

    /** @test */
    // Autor: Andrehi Sandoval
    public function buscador_vacio_muestra_validacion(): void
    {
        Http::fake(['*' => Http::response([], 200)]);

        $response = $this->get('/pokemon?search=');

        $response->assertStatus(200);
        $response->assertSee('vacío');
    }
}
