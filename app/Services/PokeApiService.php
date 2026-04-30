<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class PokeApiService
{
    private const BASE_URL = 'https://pokeapi.co/api/v2';

    /**
     * Obtiene los datos de un Pokémon por nombre o ID desde la PokéAPI.
     * Devuelve el array JSON o null si no se encuentra / hay error de red.
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

    /**
     * Obtiene la lista de Pokémon con nombre y sprite ya incluidos (1 sola petición HTTP).
     */
    public function getPokemonList(int $limit = 20): array
    {
        return cache()->remember("pokemon_list_{$limit}", 300, function () use ($limit) {
            try {
                $response = Http::withoutVerifying()
                    ->withUserAgent('Pokedex/1.0')
                    ->timeout(8)
                    ->get(self::BASE_URL . "/pokemon?limit={$limit}");

                if (! $response->successful()) {
                    return [];
                }

                return array_map(function ($item) {
                    return [
                        'name'   => $item['name'],
                        'sprite' => $this->spriteFromUrl($item['url']),
                    ];
                }, $response->json()['results'] ?? []);
            } catch (\Exception $e) {
                return [];
            }
        });
    }

    /**
     * Obtiene Pokémon de un tipo con nombre y sprite (1 sola petición HTTP).
     */
    public function getPokemonByType(string $type, int $limit = 20): array
    {
        $key = "pokemon_type_{$type}_{$limit}";

        return cache()->remember($key, 300, function () use ($type, $limit) {
            try {
                $response = Http::withoutVerifying()
                    ->withUserAgent('Pokedex/1.0')
                    ->timeout(8)
                    ->get(self::BASE_URL . '/type/' . strtolower(trim($type)));

                if (! $response->successful()) {
                    return [];
                }

                $entries = array_slice($response->json()['pokemon'] ?? [], 0, $limit);

                return array_map(function ($e) {
                    return [
                        'name'   => $e['pokemon']['name'],
                        'sprite' => $this->spriteFromUrl($e['pokemon']['url']),
                    ];
                }, $entries);
            } catch (\Exception $e) {
                return [];
            }
        });
    }

    /** Extrae el ID del Pokémon desde su URL y devuelve la URL del sprite oficial. */
    private function spriteFromUrl(string $url): string
    {
        preg_match('/\/(\d+)\/$/', $url, $m);
        $id = $m[1] ?? '0';
        return "https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/{$id}.png";
    }
}
