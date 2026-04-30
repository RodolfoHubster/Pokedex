<?php

namespace App\Services;

class PokemonMapper
{
    /**
     * Transforma el JSON crudo de la PokéAPI en un arreglo normalizado.
     *
     * @param  array<string, mixed>  $data
     * @return array{name: string, sprite: string|null, types: list<string>, hp: int, attack: int, defense: int}
     */
    public function map(array $data): array
    {
        $name   = strtolower(trim($data['name'] ?? ''));
        $sprite = $data['sprites']['front_default'] ?? null;

        $types = array_map(
            fn ($t) => $t['type']['name'],
            $data['types'] ?? []
        );

        $stats   = collect($data['stats'] ?? []);
        $hp      = (int) ($stats->firstWhere('stat.name', 'hp')['base_stat']      ?? 0);
        $attack  = (int) ($stats->firstWhere('stat.name', 'attack')['base_stat']  ?? 0);
        $defense = (int) ($stats->firstWhere('stat.name', 'defense')['base_stat'] ?? 0);

        return compact('name', 'sprite', 'types', 'hp', 'attack', 'defense');
    }
}
