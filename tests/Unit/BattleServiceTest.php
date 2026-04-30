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
    public function winner_devuelve_A_cuando_scoreA_es_mayor(): void
    {
        $this->assertSame('A', $this->service->winner(200, 150));
    }

    /** @test */
    // Autor: Andrehi Sandoval
    public function winner_devuelve_B_cuando_scoreB_es_mayor(): void
    {
        $this->assertSame('B', $this->service->winner(100, 180));
    }

    /** @test */
    // Autor: Andrehi Sandoval
    public function winner_devuelve_EMPATE_cuando_scores_iguales(): void
    {
        $this->assertSame('EMPATE', $this->service->winner(150, 150));
    }
}
