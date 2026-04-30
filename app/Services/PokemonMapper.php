<?php

namespace App\Services;

class PokemonMapper
{
    /**
     * Convierte el array JSON de la PokéAPI en una estructura limpia con
     * las llaves: name, sprite, types, hp, attack, defense.
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
