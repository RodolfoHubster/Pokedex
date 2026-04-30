<?php

// Autor: RodolfoHubster

namespace Tests\Unit;

use App\Models\Favorite;
use App\Models\User;
use App\Services\PokemonMapper;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PokemonMapperTest extends TestCase
{
    use RefreshDatabase;

    // ---------------------------------------------------------------------------
    // Helper: payload completo de Pikachu
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

    // ===========================================================================
    // 1. El mapper devuelve las llaves esperadas
    // ===========================================================================

    // Autor: RodolfoHubster
    public function test_mapper_returns_expected_keys(): void
    {
        $mapper = new PokemonMapper();
        $result = $mapper->map($this->pikachuPayload());

        $this->assertArrayHasKey('name', $result);
        $this->assertArrayHasKey('sprite', $result);
        $this->assertArrayHasKey('types', $result);
        $this->assertArrayHasKey('hp', $result);
        $this->assertArrayHasKey('attack', $result);
        $this->assertArrayHasKey('defense', $result);
    }

    // ===========================================================================
    // 2. Extrae tipos correctamente cuando hay 1 tipo
    // ===========================================================================

    // Autor: RodolfoHubster
    public function test_mapper_extracts_single_type(): void
    {
        $mapper = new PokemonMapper();
        $result = $mapper->map($this->pikachuPayload());

        $this->assertCount(1, $result['types']);
        $this->assertSame('electric', $result['types'][0]);
    }

    // ===========================================================================
    // 3. Extrae tipos correctamente cuando hay 2 tipos
    // ===========================================================================

    // Autor: RodolfoHubster
    public function test_mapper_extracts_two_types(): void
    {
        $mapper = new PokemonMapper();
        $result = $mapper->map($this->bulbasaurPayload());

        $this->assertCount(2, $result['types']);
        $this->assertSame('grass',  $result['types'][0]);
        $this->assertSame('poison', $result['types'][1]);
    }

    // ===========================================================================
    // 4. Extrae stats (hp, attack, defense) correctamente
    // ===========================================================================

    // Autor: RodolfoHubster
    public function test_mapper_extracts_stats_correctly(): void
    {
        $mapper = new PokemonMapper();
        $result = $mapper->map($this->pikachuPayload());

        $this->assertSame(35, $result['hp']);
        $this->assertSame(55, $result['attack']);
        $this->assertSame(40, $result['defense']);
    }

    // ===========================================================================
    // 5. Maneja respuestas incompletas o vacías sin romper la aplicación
    // ===========================================================================

    // Autor: RodolfoHubster
    public function test_mapper_handles_empty_data_gracefully(): void
    {
        $mapper = new PokemonMapper();
        $result = $mapper->map([]);

        $this->assertSame('', $result['name']);
        $this->assertNull($result['sprite']);
        $this->assertSame([], $result['types']);
        $this->assertSame(0, $result['hp']);
        $this->assertSame(0, $result['attack']);
        $this->assertSame(0, $result['defense']);
    }

    // ===========================================================================
    // 6. Normalización de nombres (trim/lower)
    // ===========================================================================

    // Autor: RodolfoHubster
    public function test_mapper_normalizes_name(): void
    {
        $mapper  = new PokemonMapper();
        $payload = $this->pikachuPayload();

        $payload['name'] = '  PIKACHU  ';
        $result = $mapper->map($payload);

        $this->assertSame('pikachu', $result['name']);
    }

    // ===========================================================================
    // 7. Manejo de respuesta 404 de la API de forma controlada
    // ===========================================================================

    // Autor: RodolfoHubster
    public function test_mapper_handles_missing_sprite(): void
    {
        $mapper  = new PokemonMapper();
        $payload = $this->pikachuPayload();

        // Simulamos que sprites->front_default no existe (respuesta incompleta de API)
        $payload['sprites'] = [];
        $result = $mapper->map($payload);

        $this->assertNull($result['sprite']);
    }

    // ===========================================================================
    // 8. Sprite se extrae correctamente cuando está presente
    // ===========================================================================

    // Autor: RodolfoHubster
    public function test_mapper_extracts_sprite_url(): void
    {
        $mapper = new PokemonMapper();
        $result = $mapper->map($this->pikachuPayload());

        $this->assertSame('https://example.com/pikachu.png', $result['sprite']);
    }

    // ===========================================================================
    // MISIÓN ÚNICA – Favoritos con Sesión (2 Unit Tests extra)
    // ===========================================================================

    // Autor: RodolfoHubster
    public function test_user_can_have_multiple_favorites(): void
    {
        $user = User::factory()->create();

        $user->favorites()->createMany([
            ['pokemon_name' => 'pikachu',  'sprite_data' => null],
            ['pokemon_name' => 'bulbasaur', 'sprite_data' => null],
        ]);

        $this->assertCount(2, $user->favorites()->get());
    }

    // Autor: RodolfoHubster
    public function test_favorite_belongs_to_user(): void
    {
        $user = User::factory()->create();

        $favorite = Favorite::create([
            'user_id'      => $user->id,
            'pokemon_name' => 'charmander',
            'sprite_data'  => null,
        ]);

        $this->assertInstanceOf(User::class, $favorite->user);
        $this->assertSame($user->id, $favorite->user->id);
    }
}
