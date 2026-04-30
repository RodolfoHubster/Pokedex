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
        try {
            $response = Http::withoutVerifying()
                ->withUserAgent('Pokedex/1.0')
                ->timeout(5)
                ->get(self::BASE_URL . '/pokemon/' . strtolower(trim($name)));

            return $response->successful() ? $response->json() : null;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Obtiene la lista de Pokémon (nombre + url) desde la PokéAPI.
     */
    public function getPokemonList(int $limit = 20): array
    {
        try {
            $response = Http::withoutVerifying()
                ->withUserAgent('Pokedex/1.0')
                ->timeout(5)
                ->get(self::BASE_URL . "/pokemon?limit={$limit}");

            return $response->successful() ? ($response->json()['results'] ?? []) : [];
        } catch (\Exception $e) {
            return [];
        }
    }
}
