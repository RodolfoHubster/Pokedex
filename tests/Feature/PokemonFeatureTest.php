<?php

// Autor: RodolfoHubster

namespace Tests\Feature;

use App\Models\Favorite;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class PokemonFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
    }

    // ---------------------------------------------------------------------------
    // Datos de muestra reutilizables para los fakes HTTP
    // ---------------------------------------------------------------------------

    private function pikachuPayload(): array
    {
        return [
            'name'    => 'pikachu',
            'sprites' => ['front_default' => 'https://example.com/pikachu.png'],
            'types'   => [
                ['type' => ['name' => 'electric']],
            ],
            'stats' => [
                ['base_stat' => 35, 'stat' => ['name' => 'hp']],
                ['base_stat' => 55, 'stat' => ['name' => 'attack']],
                ['base_stat' => 40, 'stat' => ['name' => 'defense']],
            ],
        ];
    }

    private function listPayload(): array
    {
        return [
            'results' => [
                ['name' => 'bulbasaur',  'url' => 'https://pokeapi.co/api/v2/pokemon/1/'],
                ['name' => 'charmander', 'url' => 'https://pokeapi.co/api/v2/pokemon/4/'],
            ],
        ];
    }

    private function bulbasaurPayload(): array
    {
        return [
            'name'    => 'bulbasaur',
            'sprites' => ['front_default' => 'https://example.com/bulbasaur.png'],
            'types'   => [
                ['type' => ['name' => 'grass']],
                ['type' => ['name' => 'poison']],
            ],
            'stats' => [
                ['base_stat' => 45, 'stat' => ['name' => 'hp']],
                ['base_stat' => 49, 'stat' => ['name' => 'attack']],
                ['base_stat' => 49, 'stat' => ['name' => 'defense']],
            ],
        ];
    }

    private function charmanderPayload(): array
    {
        return [
            'name'    => 'charmander',
            'sprites' => ['front_default' => 'https://example.com/charmander.png'],
            'types'   => [
                ['type' => ['name' => 'fire']],
            ],
            'stats' => [
                ['base_stat' => 39, 'stat' => ['name' => 'hp']],
                ['base_stat' => 52, 'stat' => ['name' => 'attack']],
                ['base_stat' => 43, 'stat' => ['name' => 'defense']],
            ],
        ];
    }

    // ===========================================================================
    // 1. GET / (Home) responde 200
    // ===========================================================================

    // Autor: RodolfoHubster
    public function test_home_returns_200(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    // ===========================================================================
    // 2. GET /pokemon (Listado) responde 200
    // ===========================================================================

    // Autor: RodolfoHubster
    public function test_pokemon_index_returns_200(): void
    {
        Http::fake([
            'https://pokeapi.co/api/v2/pokemon?limit=20' => Http::response($this->listPayload(), 200),
            'https://pokeapi.co/api/v2/pokemon/1/'       => Http::response($this->bulbasaurPayload(), 200),
            'https://pokeapi.co/api/v2/pokemon/4/'       => Http::response($this->charmanderPayload(), 200),
        ]);

        $response = $this->get('/pokemon');

        $response->assertStatus(200);
    }

    // ===========================================================================
    // 3. GET /about responde 200
    // ===========================================================================

    // Autor: RodolfoHubster
    public function test_about_returns_200(): void
    {
        $response = $this->get('/about');

        $response->assertStatus(200);
    }

    // ===========================================================================
    // 4. GET /pokemon/pikachu responde 200
    // ===========================================================================

    // Autor: RodolfoHubster
    public function test_pokemon_show_pikachu_returns_200(): void
    {
        Http::fake([
            'https://pokeapi.co/api/v2/pokemon/pikachu' => Http::response($this->pikachuPayload(), 200),
        ]);

        $response = $this->get('/pokemon/pikachu');

        $response->assertStatus(200);
    }

    // ===========================================================================
    // 5. GET /pokemon/nombreinvalido maneja el error de forma controlada
    // ===========================================================================

    // Autor: RodolfoHubster
    public function test_pokemon_show_invalid_name_handles_error(): void
    {
        Http::fake([
            'https://pokeapi.co/api/v2/pokemon/nombreinvalido' => Http::response([], 404),
        ]);

        $response = $this->get('/pokemon/nombreinvalido');

        // El controlador devuelve la vista de error (status 200) en lugar de lanzar excepción
        $this->assertTrue(
            in_array($response->status(), [200, 404]),
            'Expected 200 (friendly error view) or 404'
        );
    }

    // ===========================================================================
    // 6. Buscador vacío en /pokemon muestra mensaje de error de validación
    // ===========================================================================

    // Autor: RodolfoHubster
    public function test_empty_search_shows_validation_error(): void
    {
        $response = $this->get('/pokemon?search=');

        $response->assertStatus(200);
        $response->assertSee('El campo de búsqueda no puede estar vacío.');
    }

    // ===========================================================================
    // MISIÓN ÚNICA – Favoritos con Sesión (2 Feature Tests extra)
    // ===========================================================================

    // Autor: RodolfoHubster
    public function test_authenticated_user_can_store_favorite(): void
    {
        Http::fake([
            'https://pokeapi.co/api/v2/pokemon/pikachu' => Http::response($this->pikachuPayload(), 200),
            'https://example.com/pikachu.png'           => Http::response('fake-image-data', 200),
        ]);

        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->post('/favorites', ['pokemon_name' => 'pikachu']);

        $response->assertRedirect();
        $this->assertDatabaseHas('favorites', [
            'user_id'      => $user->id,
            'pokemon_name' => 'pikachu',
        ]);
    }

    // Autor: RodolfoHubster
    public function test_authenticated_user_can_remove_favorite(): void
    {
        $user = User::factory()->create();

        Favorite::create([
            'user_id'      => $user->id,
            'pokemon_name' => 'bulbasaur',
            'sprite_data'  => null,
        ]);

        $response = $this->actingAs($user)
            ->delete('/favorites/bulbasaur');

        $response->assertRedirect();
        $this->assertDatabaseMissing('favorites', [
            'user_id'      => $user->id,
            'pokemon_name' => 'bulbasaur',
        ]);
    }
}
