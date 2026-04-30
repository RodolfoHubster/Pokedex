<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class PokeApiService
{
    protected string $baseUrl = 'https://pokeapi.co/api/v2';

    /**
     * Obtiene el listado de Pokémon desde la PokéAPI.
     *
     * @param  int  $limit
     * @return array<int, array{name: string, url: string}>
     */
    public function getList(int $limit = 20): array
    {
        $response = Http::withoutVerifying()
            ->withUserAgent('Pokedex/1.0')
            ->timeout(5)
            ->get("{$this->baseUrl}/pokemon?limit={$limit}");

        if (! $response->successful()) {
            return [];
        }

        return $response->json()['results'] ?? [];
    }

    /**
     * Obtiene el detalle de un Pokémon por nombre o ID.
     *
     * @param  string  $name
     * @return array<string, mixed>|null  null cuando la API responde 404
     */
    public function getPokemon(string $name): ?array
    {
        $response = Http::withoutVerifying()
            ->withUserAgent('Pokedex/1.0')
            ->timeout(5)
            ->get("{$this->baseUrl}/pokemon/{$name}");

        if (! $response->successful()) {
            return null;
        }

        return $response->json();
    }
}
