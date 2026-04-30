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
