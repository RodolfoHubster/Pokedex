<?php

namespace Tests\Unit;

use App\Services\PokemonMapper;
use PHPUnit\Framework\TestCase;

class PokemonMapperTest extends TestCase
{
    private PokemonMapper $mapper;

    protected function setUp(): void
    {
        parent::setUp();
        $this->mapper = new PokemonMapper();
    }

    // ──────────────────────────────────────────────
    //  Autor: Andrehi Sandoval
    // ──────────────────────────────────────────────

    /** El mapper devuelve un arreglo con las llaves esperadas. */
    public function test_map_returns_expected_keys(): void
    {
        // Autor: Andrehi Sandoval
        $data   = $this->pikachuData();
        $result = $this->mapper->map($data);

        $this->assertArrayHasKey('name', $result);
        $this->assertArrayHasKey('sprite', $result);
        $this->assertArrayHasKey('types', $result);
        $this->assertArrayHasKey('hp', $result);
        $this->assertArrayHasKey('attack', $result);
        $this->assertArrayHasKey('defense', $result);
    }

    /** Extrae tipos correctamente cuando hay 1 tipo. */
    public function test_map_extracts_single_type(): void
    {
        // Autor: Andrehi Sandoval
        $data         = $this->pikachuData(); // solo tiene 'electric'
        $result       = $this->mapper->map($data);

        $this->assertCount(1, $result['types']);
        $this->assertSame('electric', $result['types'][0]);
    }

    /** Extrae tipos correctamente cuando hay 2 tipos. */
    public function test_map_extracts_two_types(): void
    {
        // Autor: Andrehi Sandoval
        $data = $this->pikachuData();
        $data['types'][] = ['type' => ['name' => 'steel']];

        $result = $this->mapper->map($data);

        $this->assertCount(2, $result['types']);
        $this->assertSame('electric', $result['types'][0]);
        $this->assertSame('steel', $result['types'][1]);
    }

    /** Extrae stats hp/attack/defense correctamente. */
    public function test_map_extracts_stats(): void
    {
        // Autor: Andrehi Sandoval
        $result = $this->mapper->map($this->pikachuData());

        $this->assertSame(35, $result['hp']);
        $this->assertSame(55, $result['attack']);
        $this->assertSame(40, $result['defense']);
    }

    // ──────────────────────────────────────────────
    //  Autor: Rodolfo Huitron
    // ──────────────────────────────────────────────

    /** Maneja respuesta incompleta (faltan campos) sin romper. */
    public function test_map_handles_incomplete_data(): void
    {
        // Autor: Rodolfo Huitron
        $incomplete = ['name' => 'missingno']; // sin types, stats ni sprites
        $result     = $this->mapper->map($incomplete);

        $this->assertSame('missingno', $result['name']);
        $this->assertNull($result['sprite']);
        $this->assertSame([], $result['types']);
        $this->assertSame(0, $result['hp']);
        $this->assertSame(0, $result['attack']);
        $this->assertSame(0, $result['defense']);
    }

    /** Maneja respuesta vacía sin romper. */
    public function test_map_handles_empty_data(): void
    {
        // Autor: Rodolfo Huitron
        $result = $this->mapper->map([]);

        $this->assertSame('', $result['name']);
        $this->assertNull($result['sprite']);
        $this->assertSame([], $result['types']);
        $this->assertSame(0, $result['hp']);
        $this->assertSame(0, $result['attack']);
        $this->assertSame(0, $result['defense']);
    }

    /** Normalización de nombre (trim/lower). */
    public function test_normalize_name_trims_and_lowercases(): void
    {
        // Autor: Rodolfo Huitron
        $this->assertSame('pikachu', $this->mapper->normalizeName('  PIKACHU  '));
        $this->assertSame('bulbasaur', $this->mapper->normalizeName('Bulbasaur'));
        $this->assertSame('mr-mime', $this->mapper->normalizeName('Mr-Mime'));
    }

    /** Manejo de "pokémon no encontrado" (null del servicio) de forma controlada. */
    public function test_map_with_null_returns_defaults_when_called_with_empty(): void
    {
        // Autor: Rodolfo Huitron
        // Cuando la API devuelve null el controlador no llama a map(); pero si
        // se llama con [] (equivalente de respuesta vacía) no debe lanzar excepción.
        $result = $this->mapper->map([]);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('name', $result);
    }

    // ──────────────────────────────────────────────
    //  Helper
    // ──────────────────────────────────────────────

    private function pikachuData(): array
    {
        return [
            'name'    => 'pikachu',
            'sprites' => ['front_default' => 'https://example.com/pikachu.png'],
            'types'   => [
                ['type' => ['name' => 'electric']],
            ],
            'stats' => [
                ['base_stat' => 35],  // hp
                ['base_stat' => 55],  // attack
                ['base_stat' => 40],  // defense
            ],
        ];
    }
}
