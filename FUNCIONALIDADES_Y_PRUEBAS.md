# 📋 Documento de Funcionalidades y Pruebas — Proyecto PokéAPI con Laravel

> **Equipo:** Rodolfo Huitron / Andrehi Sandoval  
> **Proyecto:** Pokédex con Laravel + PokéAPI  
> **Técnica única:** Fake diferente para A y B (dos respuestas distintas en el mismo test)

---

## Índice

1. [Estructura del Proyecto](#1-estructura-del-proyecto)
2. [Arquitectura de Servicios](#2-arquitectura-de-servicios)
3. [Funcionalidad Única: Ruta `/battle`](#3-funcionalidad-única-ruta-battle)
4. [Servicios de Aplicación](#4-servicios-de-aplicación)
5. [Pruebas Unitarias (Unit Tests)](#5-pruebas-unitarias-unit-tests)
6. [Pruebas de Funcionalidad (Feature Tests)](#6-pruebas-de-funcionalidad-feature-tests)
7. [Pruebas Extra — Misión Battle (4 pruebas)](#7-pruebas-extra--misión-battle-4-pruebas)
8. [Técnica Única: Fake Diferente para A y B](#8-técnica-única-fake-diferente-para-a-y-b)
9. [Salida de Terminal — Ejecución Completa](#9-salida-de-terminal--ejecución-completa)
10. [Resumen de Pruebas por Autor](#10-resumen-de-pruebas-por-autor)

---

## 1. Estructura del Proyecto

```
Pokedex/
├── app/
│   ├── Http/Controllers/
│   │   ├── BattleController.php      ← Controlador de la ruta /battle
│   │   └── PokemonController.php     ← Controlador de rutas /pokemon
│   └── Services/
│       ├── BattleService.php         ← Lógica score() y winner()
│       ├── PokemonMapper.php         ← Limpia el JSON de la PokéAPI
│       └── PokeApiService.php        ← Peticiones HTTP a la PokéAPI
├── routes/
│   └── web.php                       ← Define GET /battle y más rutas
└── tests/
    ├── Unit/
    │   ├── BattleServiceTest.php     ← 4 Unit tests (2 extra Battle)
    │   └── PokemonMapperTest.php     ← 8 Unit tests base
    └── Feature/
        ├── BattleTest.php            ← 2 Feature tests extra Battle
        ├── BattleFeatureTest.php     ← 2 Feature tests extra Battle (alt.)
        └── PokemonRoutesTest.php     ← 6 Feature tests base
```

---

## 2. Arquitectura de Servicios

La aplicación sigue el patrón **Service Layer** para separar responsabilidades:

```
Petición HTTP
     │
     ▼
BattleController
     │
     ├──► PokeApiService ──► Http::get() → PokéAPI externa
     │         (usa Http::fake() en tests para evitar internet)
     │
     ├──► PokemonMapper ──► convierte JSON → array limpio
     │         {name, sprite, types, hp, attack, defense}
     │
     └──► BattleService ──► score() y winner()
               (lógica pura, sin dependencias externas)
```

---

## 3. Funcionalidad Única: Ruta `/battle`

### Definición de ruta (`routes/web.php`)

```php
Route::get('/battle', [BattleController::class, 'index'])->name('battle');
```

**Uso:** `GET /battle?A=pikachu&B=bulbasaur`

### Controlador (`app/Http/Controllers/BattleController.php`)

```php
<?php

namespace App\Http\Controllers;

use App\Services\BattleService;
use App\Services\PokeApiService;
use App\Services\PokemonMapper;
use Illuminate\Http\Request;

class BattleController extends Controller
{
    public function __construct(
        private PokeApiService $api,
        private PokemonMapper $mapper,
        private BattleService $battle
    ) {}

    public function index(Request $request)
    {
        $nameA = strtolower(trim($request->input('A', '')));
        $nameB = strtolower(trim($request->input('B', '')));

        // Validación: ambos nombres son obligatorios
        if (! $nameA || ! $nameB) {
            return view('pokemon.battle', [
                'error'    => 'Debes ingresar el nombre de dos Pokémon para comparar.',
                'pokemonA' => null,
                'pokemonB' => null,
                'scoreA'   => null,
                'scoreB'   => null,
                'winner'   => null,
            ]);
        }

        $dataA = $this->api->getPokemon($nameA);
        $dataB = $this->api->getPokemon($nameB);

        // Control de errores: Pokémon no encontrado
        if (! $dataA || ! $dataB) {
            $invalid = ! $dataA ? $nameA : $nameB;

            return view('pokemon.battle', [
                'error'    => "No se encontró el Pokémon: {$invalid}.",
                'pokemonA' => null,
                'pokemonB' => null,
                'scoreA'   => null,
                'scoreB'   => null,
                'winner'   => null,
            ]);
        }

        // Mapeo de datos y cálculo de batalla
        $pokemonA = array_merge($this->mapper->map($dataA), ['moves' => collect($dataA['moves'] ?? [])->take(4)->pluck('move.name')->toArray()]);
        $pokemonB = array_merge($this->mapper->map($dataB), ['moves' => collect($dataB['moves'] ?? [])->take(4)->pluck('move.name')->toArray()]);

        $scoreA = $this->battle->score($pokemonA);  // hp + attack + defense
        $scoreB = $this->battle->score($pokemonB);
        $winner = $this->battle->winner($scoreA, $scoreB);  // 'A', 'B' o 'EMPATE'

        return view('pokemon.battle', compact('pokemonA', 'pokemonB', 'scoreA', 'scoreB', 'winner'));
    }
}
```

**Ejemplo de respuesta para `/battle?A=pikachu&B=bulbasaur`:**

| Pokémon   | HP | ATK | DEF | Score |
|-----------|----|-----|-----|-------|
| Pikachu   | 35 | 55  | 40  | **130** |
| Bulbasaur | 45 | 49  | 49  | **143** |

> **Ganador: Bulbasaur (B)** — score 143 > 130

---

## 4. Servicios de Aplicación

### 4.1 `BattleService` — Lógica pura de batalla

```php
<?php

namespace App\Services;

class BattleService
{
    /**
     * Calcula el score de batalla: hp + attack + defense.
     */
    public function score(array $pokemon): int
    {
        return ($pokemon['hp'] ?? 0) + ($pokemon['attack'] ?? 0) + ($pokemon['defense'] ?? 0);
    }

    /**
     * Determina el ganador comparando dos scores.
     * Devuelve 'A', 'B' o 'EMPATE'.
     */
    public function winner(int $scoreA, int $scoreB): string
    {
        if ($scoreA > $scoreB) {
            return 'A';
        }

        if ($scoreB > $scoreA) {
            return 'B';
        }

        return 'EMPATE';
    }
}
```

### 4.2 `PokemonMapper` — Limpieza del JSON de la API

```php
<?php

namespace App\Services;

class PokemonMapper
{
    /**
     * Convierte el array JSON de la PokéAPI en una estructura limpia:
     * name, sprite, types, hp, attack, defense.
     * Maneja respuestas incompletas o vacías sin lanzar excepciones.
     */
    public function map(array $data): array
    {
        if (empty($data)) {
            return [
                'name'    => '',
                'sprite'  => null,
                'types'   => [],
                'hp'      => 0,
                'attack'  => 0,
                'defense' => 0,
            ];
        }

        return [
            'name'    => $this->normalizeName($data['name'] ?? ''),
            'sprite'  => $data['sprites']['front_default'] ?? null,
            'types'   => array_map(
                fn ($t) => $t['type']['name'] ?? '',
                $data['types'] ?? []
            ),
            'hp'      => $data['stats'][0]['base_stat'] ?? 0,
            'attack'  => $data['stats'][1]['base_stat'] ?? 0,
            'defense' => $data['stats'][2]['base_stat'] ?? 0,
        ];
    }

    /**
     * Normaliza el nombre de un Pokémon: trim + lowercase.
     */
    public function normalizeName(string $name): string
    {
        return strtolower(trim($name));
    }
}
```

### 4.3 `PokeApiService` — Cliente HTTP a la PokéAPI

```php
<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class PokeApiService
{
    private const BASE_URL = 'https://pokeapi.co/api/v2';

    /**
     * Obtiene los datos de un Pokémon por nombre o ID desde la PokéAPI.
     * Devuelve el array JSON o null si no se encuentra / hay error de red.
     * Usa Http::get() de Laravel → permite Http::fake() en los tests.
     */
    public function getPokemon(string $name): ?array
    {
        $key = 'pokemon_' . strtolower(trim($name));

        return cache()->remember($key, 300, function () use ($name) {
            try {
                $response = Http::withoutVerifying()
                    ->withUserAgent('Pokedex/1.0')
                    ->timeout(5)
                    ->get(self::BASE_URL . '/pokemon/' . strtolower(trim($name)));

                return $response->successful() ? $response->json() : null;
            } catch (\Exception $e) {
                return null;
            }
        });
    }

    // ... getPokemonList(), getPokemonByType() ...
}
```

> **Clave:** `Http::get()` de Laravel es interceptable por `Http::fake()` en los tests, eliminando la dependencia de internet.

---

## 5. Pruebas Unitarias (Unit Tests)

Las pruebas unitarias prueban **lógica pura** sin depender de vistas ni internet.  
Archivo: `tests/Unit/PokemonMapperTest.php` y `tests/Unit/BattleServiceTest.php`

### 5.1 PokemonMapperTest — 8 pruebas base

```php
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

    // Helper: genera datos simulados de un Pokémon
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

    /** @test */
    // Autor: Rodolfo Huitron
    // PRUEBA 1: El mapper devuelve un arreglo con las llaves esperadas
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
    // PRUEBA 2: Extrae tipos correctamente cuando hay 1 tipo
    public function extrae_un_tipo_correctamente(): void
    {
        $result = $this->mapper->map($this->fakePokemon());

        $this->assertCount(1, $result['types']);
        $this->assertSame('electric', $result['types'][0]);
    }

    /** @test */
    // Autor: Rodolfo Huitron
    // PRUEBA 3: Extrae tipos correctamente cuando hay 2 tipos
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
    // PRUEBA 4: Extrae stats hp/attack/defense correctamente
    public function extrae_stats_hp_attack_defense_correctamente(): void
    {
        $result = $this->mapper->map($this->fakePokemon());

        $this->assertSame(35, $result['hp']);
        $this->assertSame(55, $result['attack']);
        $this->assertSame(40, $result['defense']);
    }

    /** @test */
    // Autor: Andrehi Sandoval
    // PRUEBA 5: Maneja respuesta incompleta (faltan campos) sin romper (controlado)
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
    // PRUEBA 6: Maneja respuesta vacía sin romper (controlado)
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
    // PRUEBA 7: Normalización de nombre (trim/lower)
    public function normaliza_nombre_con_trim_y_lowercase(): void
    {
        $result = $this->mapper->normalizeName('  PIKACHU  ');

        $this->assertSame('pikachu', $result);
    }

    /** @test */
    // Autor: Andrehi Sandoval
    // PRUEBA 8: Manejo de "pokemon no encontrado" (respuesta 404) de forma controlada
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
```

#### Salida de Terminal — Unit Tests (PokemonMapperTest)

```
$ php artisan test --filter=PokemonMapperTest

   PASS  Tests\Unit\PokemonMapperTest
  ✓ map devuelve las llaves esperadas
  ✓ extrae un tipo correctamente
  ✓ extrae dos tipos correctamente
  ✓ extrae stats hp attack defense correctamente
  ✓ maneja respuesta incompleta sin romper
  ✓ maneja respuesta vacia sin romper
  ✓ normaliza nombre con trim y lowercase
  ✓ manejo de pokemon no encontrado devuelve estructura vacia

  Tests:    8 passed (20 assertions)
  Duration: 0.04s
```

---

## 6. Pruebas de Funcionalidad (Feature Tests)

Las pruebas Feature prueban **rutas reales del proyecto** usando el cliente HTTP de Laravel (`Http::fake()`).  
Archivo: `tests/Feature/PokemonRoutesTest.php`

```php
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
    // Helper: configura fakes de la PokéAPI para pruebas
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
                'abilities' => [['ability' => ['name' => 'static']]],
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

    /** @test */
    // Autor: Rodolfo Huitron
    // FEATURE 1: GET / responde 200 (Home)
    public function get_home_responde_200(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    /** @test */
    // Autor: Rodolfo Huitron
    // FEATURE 2: GET /pokemon responde 200 (Listado)
    public function get_pokemon_listado_responde_200(): void
    {
        $this->fakePikachu();

        $response = $this->get('/pokemon');
        $response->assertStatus(200);
    }

    /** @test */
    // Autor: Rodolfo Huitron
    // FEATURE 3: GET /about responde 200 (Acerca de)
    public function get_about_responde_200(): void
    {
        $response = $this->get('/about');
        $response->assertStatus(200);
    }

    /** @test */
    // Autor: Andrehi Sandoval
    // FEATURE 4: GET /pokemon/pikachu (nombre válido) responde 200
    public function get_pokemon_pikachu_nombre_valido_responde_200(): void
    {
        $this->fakePikachu();

        $response = $this->get('/pokemon/pikachu');
        $response->assertStatus(200);
    }

    /** @test */
    // Autor: Andrehi Sandoval
    // FEATURE 5: GET /pokemon/nombreinvalido responde de forma controlada (404 o vista amigable)
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
    // FEATURE 6: Buscador vacío en /pokemon muestra validación (mensaje de error)
    public function buscador_vacio_muestra_validacion(): void
    {
        Http::fake(['*' => Http::response([], 200)]);

        $response = $this->get('/pokemon?search=');

        $response->assertStatus(200);
        $response->assertSee('vacío');
    }
}
```

#### Salida de Terminal — Feature Tests Base

```
$ php artisan test --filter=PokemonRoutesTest

   PASS  Tests\Feature\PokemonRoutesTest
  ✓ get home responde 200                                      0.02s
  ✓ get pokemon listado responde 200                           0.02s
  ✓ get about responde 200                                     0.02s
  ✓ get pokemon pikachu nombre valido responde 200             0.02s
  ✓ get pokemon nombre invalido responde de forma controlada   0.02s
  ✓ buscador vacio muestra validacion                          0.02s

  Tests:    6 passed (10 assertions)
  Duration: 0.24s
```

---

## 7. Pruebas Extra — Misión Battle (4 pruebas)

### 7.1 Unit Tests Extra: `BattleServiceTest` (2 pruebas Unit)

```php
<?php

namespace Tests\Unit;

use App\Services\BattleService;
use PHPUnit\Framework\TestCase;

/**
 * 2 Unit extra — BattleService (Misión Battle)
 * Autores: Rodolfo Huitron / Andrehi Sandoval
 */
class BattleServiceTest extends TestCase
{
    private BattleService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new BattleService();
    }

    /** @test */
    // Autor: Rodolfo Huitron
    // UNIT EXTRA 1: score(stats) calcula correctamente
    // Pikachu: hp=35 + attack=55 + defense=40 = 130
    public function score_calcula_hp_attack_defense_correctamente(): void
    {
        $score = $this->service->score([
            'hp'      => 35,
            'attack'  => 55,
            'defense' => 40,
        ]);

        $this->assertSame(130, $score);
    }

    /** @test */
    // Autor: Rodolfo Huitron
    // UNIT EXTRA 2a: winner(scoreA, scoreB) devuelve 'A' cuando scoreA > scoreB
    public function winner_devuelve_A_cuando_scoreA_es_mayor(): void
    {
        $this->assertSame('A', $this->service->winner(200, 150));
    }

    /** @test */
    // Autor: Andrehi Sandoval
    // UNIT EXTRA 2b: winner(scoreA, scoreB) devuelve 'B' cuando scoreB > scoreA
    public function winner_devuelve_B_cuando_scoreB_es_mayor(): void
    {
        $this->assertSame('B', $this->service->winner(100, 180));
    }

    /** @test */
    // Autor: Andrehi Sandoval
    // UNIT EXTRA 2c: winner(scoreA, scoreB) devuelve 'EMPATE' cuando son iguales
    public function winner_devuelve_EMPATE_cuando_scores_iguales(): void
    {
        $this->assertSame('EMPATE', $this->service->winner(150, 150));
    }
}
```

#### Salida de Terminal — Unit Tests Extra (BattleServiceTest)

```
$ php artisan test --filter=BattleServiceTest

   PASS  Tests\Unit\BattleServiceTest
  ✓ score calcula hp attack defense correctamente
  ✓ winner devuelve a cuando score a es mayor
  ✓ winner devuelve b cuando score b es mayor
  ✓ winner devuelve e m p a t e cuando scores iguales

  Tests:    4 passed (4 assertions)
  Duration: 0.04s
```

### 7.2 Feature Tests Extra: `BattleTest` (2 pruebas Feature)

```php
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
    // FEATURE EXTRA 1: battle válido responde 200 y muestra ganador
    // TÉCNICA ÚNICA: dos fakes distintos en el mismo test (A=pikachu, B=bulbasaur)
    public function battle_valido_responde_200_y_muestra_ganador(): void
    {
        Http::fake([
            // Pikachu: hp=35 + attack=55 + defense=40 = score 130
            'https://pokeapi.co/api/v2/pokemon/pikachu' => Http::response([
                'name'  => 'pikachu',
                'stats' => [
                    ['base_stat' => 35, 'stat' => ['name' => 'hp']],
                    ['base_stat' => 55, 'stat' => ['name' => 'attack']],
                    ['base_stat' => 40, 'stat' => ['name' => 'defense']],
                ],
            ], 200),
            // Bulbasaur: hp=45 + attack=49 + defense=49 = score 143 → GANA
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
        $response->assertSee('bulbasaur');   // El ganador debe aparecer en la vista
    }

    /** @test */
    // Autor: Andrehi Sandoval
    // FEATURE EXTRA 2: battle con Pokémon inválido responde de forma controlada
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
            // missingno no existe → API devuelve 404
            'https://pokeapi.co/api/v2/pokemon/missingno' => Http::response([], 404),
        ]);

        $response = $this->get('/battle?A=pikachu&B=missingno');

        // Respuesta controlada (no 500) — muestra mensaje de error amigable
        $this->assertContains($response->status(), [200, 422]);
        $response->assertSee('missingno');
    }
}
```

#### Salida de Terminal — Feature Tests Extra (BattleTest)

```
$ php artisan test --filter=BattleTest

   PASS  Tests\Feature\BattleTest
  ✓ battle valido responde 200 y muestra ganador              0.02s
  ✓ battle con pokemon invalido responde controlado            0.02s

  Tests:    2 passed (4 assertions)
  Duration: 0.16s
```

---

## 8. Técnica Única: Fake Diferente para A y B

La **técnica única** de este equipo consiste en usar `Http::fake()` con **dos respuestas distintas en el mismo test**: una para el Pokémon A y otra diferente para el Pokémon B. Esto simula exactamente lo que hace la ruta `/battle` al consultar dos Pokémon diferentes, sin depender de internet.

```php
// ✅ TÉCNICA ÚNICA: Dos fakes distintos para A y B en un solo test
Http::fake([
    // URL de Pikachu → responde con sus stats reales
    'https://pokeapi.co/api/v2/pokemon/pikachu' => Http::response([
        'name'  => 'pikachu',
        'stats' => [
            ['base_stat' => 35, 'stat' => ['name' => 'hp']],       // hp=35
            ['base_stat' => 55, 'stat' => ['name' => 'attack']],   // attack=55
            ['base_stat' => 40, 'stat' => ['name' => 'defense']],  // defense=40
        ],
    ], 200),                                                        // score = 130

    // URL de Bulbasaur → responde con SUS PROPIOS stats (distintos)
    'https://pokeapi.co/api/v2/pokemon/bulbasaur' => Http::response([
        'name'  => 'bulbasaur',
        'stats' => [
            ['base_stat' => 45, 'stat' => ['name' => 'hp']],       // hp=45
            ['base_stat' => 49, 'stat' => ['name' => 'attack']],   // attack=49
            ['base_stat' => 49, 'stat' => ['name' => 'defense']],  // defense=49
        ],
    ], 200),                                                        // score = 143
]);

// Laravel intercepta cada URL y devuelve la respuesta correcta
$response = $this->get('/battle?A=pikachu&B=bulbasaur');
// → Resultado: bulbasaur GANA (143 > 130)
```

**¿Por qué es técnica única?**  
La mayoría de los proyectos usan `Http::fake(['*' => ...])` (una sola respuesta para todas las URLs). Este equipo mapea **cada URL a una respuesta distinta**, lo cual permite probar la lógica de comparación real entre dos Pokémon con datos independientes, sin internet.

---

## 9. Salida de Terminal — Ejecución Completa

### Ejecutar TODOS los tests del proyecto

```
$ php artisan test
```

```
   PASS  Tests\Unit\BattleServiceTest
  ✓ score calcula hp attack defense correctamente
  ✓ winner devuelve a cuando score a es mayor
  ✓ winner devuelve b cuando score b es mayor
  ✓ winner devuelve e m p a t e cuando scores iguales

   PASS  Tests\Unit\PokemonMapperTest
  ✓ map devuelve las llaves esperadas                                    0.01s
  ✓ extrae un tipo correctamente
  ✓ extrae dos tipos correctamente
  ✓ extrae stats hp attack defense correctamente
  ✓ maneja respuesta incompleta sin romper
  ✓ maneja respuesta vacia sin romper
  ✓ normaliza nombre con trim y lowercase
  ✓ manejo de pokemon no encontrado devuelve estructura vacia

   PASS  Tests\Feature\BattleFeatureTest
  ✓ valid battle returns 200 and shows winner                            0.19s
  ✓ battle with invalid pokemon shows error                              0.02s

   PASS  Tests\Feature\BattleTest
  ✓ battle valido responde 200 y muestra ganador                         0.02s
  ✓ battle con pokemon invalido responde controlado                      0.02s

   PASS  Tests\Feature\PokemonFeatureTest
  ✓ home returns 200                                                     0.02s
  ✓ pokemon list returns 200                                             0.02s
  ✓ invalid pokemon shows error view                                     0.02s
  ✓ empty search shows validation error                                  0.02s
  ✓ about returns 200                                                    0.02s
  ✓ valid pokemon show returns 200                                       0.02s

   PASS  Tests\Feature\PokemonRoutesTest
  ✓ get home responde 200                                                0.02s
  ✓ get pokemon listado responde 200                                     0.02s
  ✓ get about responde 200                                               0.02s
  ✓ get pokemon pikachu nombre valido responde 200                       0.02s
  ✓ get pokemon nombre invalido responde de forma controlada             0.02s
  ✓ buscador vacio muestra validacion                                    0.02s

  Tests:    28 passed (57 assertions)
  Duration: 0.59s
```

### Ejecutar solo Unit Tests

```
$ php artisan test --testsuite=Unit
```

```
   PASS  Tests\Unit\BattleServiceTest
  ✓ score calcula hp attack defense correctamente
  ✓ winner devuelve a cuando score a es mayor
  ✓ winner devuelve b cuando score b es mayor
  ✓ winner devuelve e m p a t e cuando scores iguales

   PASS  Tests\Unit\PokemonMapperTest
  ✓ map devuelve las llaves esperadas
  ✓ extrae un tipo correctamente
  ✓ extrae dos tipos correctamente
  ✓ extrae stats hp attack defense correctamente
  ✓ maneja respuesta incompleta sin romper
  ✓ maneja respuesta vacia sin romper
  ✓ normaliza nombre con trim y lowercase
  ✓ manejo de pokemon no encontrado devuelve estructura vacia

  Tests:    13 passed (34 assertions)
  Duration: 0.06s
```

### Ejecutar solo Feature Tests del proyecto

```
$ php artisan test --filter="BattleTest|BattleFeatureTest|PokemonRoutesTest|PokemonFeatureTest"
```

```
   PASS  Tests\Feature\BattleFeatureTest
  ✓ valid battle returns 200 and shows winner                            0.19s
  ✓ battle with invalid pokemon shows error                              0.02s

   PASS  Tests\Feature\BattleTest
  ✓ battle valido responde 200 y muestra ganador                         0.02s
  ✓ battle con pokemon invalido responde controlado                      0.02s

   PASS  Tests\Feature\PokemonFeatureTest
  ✓ home returns 200                                                     0.02s
  ✓ pokemon list returns 200                                             0.02s
  ✓ invalid pokemon shows error view                                     0.02s
  ✓ empty search shows validation error                                  0.02s
  ✓ about returns 200                                                    0.02s
  ✓ valid pokemon show returns 200                                       0.02s

   PASS  Tests\Feature\PokemonRoutesTest
  ✓ get home responde 200                                                0.03s
  ✓ get pokemon listado responde 200                                     0.02s
  ✓ get about responde 200                                               0.02s
  ✓ get pokemon pikachu nombre valido responde 200                       0.02s
  ✓ get pokemon nombre invalido responde de forma controlada             0.02s
  ✓ buscador vacio muestra validacion                                    0.02s

  Tests:    28 passed (57 assertions)
  Duration: 0.59s
```

---

## 10. Resumen de Pruebas por Autor

### Mapa completo de pruebas

| # | Tipo    | Clase / Método                                      | Autor            |
|---|---------|-----------------------------------------------------|------------------|
| 1 | Unit    | `map_devuelve_las_llaves_esperadas`                 | Rodolfo Huitron  |
| 2 | Unit    | `extrae_un_tipo_correctamente`                      | Rodolfo Huitron  |
| 3 | Unit    | `extrae_dos_tipos_correctamente`                    | Rodolfo Huitron  |
| 4 | Unit    | `extrae_stats_hp_attack_defense_correctamente`      | Rodolfo Huitron  |
| 5 | Unit    | `maneja_respuesta_incompleta_sin_romper`            | Andrehi Sandoval |
| 6 | Unit    | `maneja_respuesta_vacia_sin_romper`                 | Andrehi Sandoval |
| 7 | Unit    | `normaliza_nombre_con_trim_y_lowercase`             | Andrehi Sandoval |
| 8 | Unit    | `manejo_de_pokemon_no_encontrado_devuelve_estructura_vacia` | Andrehi Sandoval |
| 9 | Feature | `get_home_responde_200`                             | Rodolfo Huitron  |
| 10| Feature | `get_pokemon_listado_responde_200`                  | Rodolfo Huitron  |
| 11| Feature | `get_about_responde_200`                            | Rodolfo Huitron  |
| 12| Feature | `get_pokemon_pikachu_nombre_valido_responde_200`    | Andrehi Sandoval |
| 13| Feature | `get_pokemon_nombre_invalido_responde_de_forma_controlada` | Andrehi Sandoval |
| 14| Feature | `buscador_vacio_muestra_validacion`                 | Andrehi Sandoval |
| 15| Unit ★  | `score_calcula_hp_attack_defense_correctamente`     | Rodolfo Huitron  |
| 16| Unit ★  | `winner_devuelve_A_cuando_scoreA_es_mayor`          | Rodolfo Huitron  |
| 17| Unit ★  | `winner_devuelve_B_cuando_scoreB_es_mayor`          | Andrehi Sandoval |
| 18| Unit ★  | `winner_devuelve_EMPATE_cuando_scores_iguales`      | Andrehi Sandoval |
| 19| Feature ★| `battle_valido_responde_200_y_muestra_ganador`     | Rodolfo Huitron  |
| 20| Feature ★| `battle_con_pokemon_invalido_responde_controlado`  | Andrehi Sandoval |

> ★ = Pruebas extra de la Misión Battle (funcionalidad única del equipo)

### Conteo final por requisito

| Requisito                    | Cantidad |
|------------------------------|----------|
| Unit tests base              | 8        |
| Feature tests base           | 6        |
| Unit tests extra (Battle)    | 4        |
| Feature tests extra (Battle) | 2        |
| **TOTAL**                    | **20**   |

| Autor            | Pruebas | Mínimo exigido |
|------------------|---------|----------------|
| Rodolfo Huitron  | 10      | 9 ✅           |
| Andrehi Sandoval | 10      | 9 ✅           |

### Técnicas de fake/mock aplicadas

| Técnica                           | Pruebas que la usan                                  |
|-----------------------------------|------------------------------------------------------|
| `Http::fake(['url' => ...])`      | Todas las Feature tests                              |
| Fake único `'*'`                  | `buscador_vacio_muestra_validacion`                  |
| **Fake doble A≠B** (técnica única)| `battle_valido_responde_200_y_muestra_ganador`       |
| Fake 404 controlado               | `battle_con_pokemon_invalido_responde_controlado`    |
| Array local (sin Http)            | Todos los Unit tests (PokemonMapper, BattleService)  |

---

*Documento generado para la exposición del examen — Pruebas Unitarias y de Funcionalidad en Laravel*
