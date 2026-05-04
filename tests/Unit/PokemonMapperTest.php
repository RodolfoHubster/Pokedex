<?php

namespace Tests\Unit;

use App\Services\PokemonMapper;
use PHPUnit\Framework\TestCase;

/**
 * 8 Unit tests — PokemonMapper
 * Autores: Rodolfo Huitron / Andrehi Sandoval
 */
class PokemonMapperTest extends TestCase
{
    private PokemonMapper $mapper;

    protected function setUp(): void
    {
        parent::setUp();
        $this->mapper = new PokemonMapper();
    }

    // ── Helpers ──────────────────────────────────────────────────────────────

    private function fakePokemon(array $overrides = []): array
    {
        return array_merge([
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
        ], $overrides);
    }

    // ── Tests ─────────────────────────────────────────────────────────────────

    /** @test */
    // Autor: Rodolfo Huitron
    public function map_devuelve_las_llaves_esperadas(): void
    {
        $result = $this->mapper->map($this->fakePokemon());

        $this->assertArrayHasKey('name',    $result);
        $this->assertArrayHasKey('sprite',  $result);
        $this->assertArrayHasKey('types',   $result);
        $this->assertArrayHasKey('hp',      $result);
        $this->assertArrayHasKey('attack',  $result);
        $this->assertArrayHasKey('defense', $result);
    }

    /** @test */
    // Autor: Rodolfo Huitron
    public function extrae_un_tipo_correctamente(): void
    {
        $result = $this->mapper->map($this->fakePokemon());

        $this->assertCount(1, $result['types']);
        $this->assertSame('electric', $result['types'][0]);
    }

    /** @test */
    // Autor: Rodolfo Huitron
    public function extrae_dos_tipos_correctamente(): void
    {
        $data = $this->fakePokemon([
            'types' => [
                ['type' => ['name' => 'water']],
                ['type' => ['name' => 'flying']],
            ],
        ]);

        $result = $this->mapper->map($data);

        $this->assertCount(2, $result['types']);
        $this->assertSame('water',  $result['types'][0]);
        $this->assertSame('flying', $result['types'][1]);
    }

    /** @test */
    // Autor: Rodolfo Huitron
    public function extrae_stats_hp_attack_defense_correctamente(): void
    {
        $result = $this->mapper->map($this->fakePokemon());

        $this->assertSame(35, $result['hp']);
        $this->assertSame(55, $result['attack']);
        $this->assertSame(40, $result['defense']);
    }

    /** @test */
    // Autor: Andrehi Sandoval
    public function maneja_respuesta_incompleta_sin_romper(): void
    {
        $result = $this->mapper->map(['name' => 'raro']);

        $this->assertSame('raro', $result['name']);
        $this->assertNull($result['sprite']);
        $this->assertSame([], $result['types']);
        $this->assertSame(0, $result['hp']);
        $this->assertSame(0, $result['attack']);
        $this->assertSame(0, $result['defense']);
    }

    /** @test */
    // Autor: Andrehi Sandoval
    public function maneja_respuesta_vacia_sin_romper(): void
    {
        $result = $this->mapper->map([]);

        $this->assertSame('',   $result['name']);
        $this->assertNull($result['sprite']);
        $this->assertSame([], $result['types']);
        $this->assertSame(0,  $result['hp']);
    }

    /** @test */
    // Autor: Andrehi Sandoval
    public function normaliza_nombre_con_trim_y_lowercase(): void
    {
        $result = $this->mapper->normalizeName('  PIKACHU  ');

        $this->assertSame('pikachu', $result);
    }

    /** @test */
    // Autor: Andrehi Sandoval
    public function manejo_de_pokemon_no_encontrado_devuelve_estructura_vacia(): void
    {
        // Simula lo que haría el controlador si la API devuelve null/vacío
        $result = $this->mapper->map([]);

        $this->assertSame('', $result['name']);
        $this->assertSame(0,  $result['hp']);
        $this->assertSame(0,  $result['attack']);
        $this->assertSame(0,  $result['defense']);
    }
}
