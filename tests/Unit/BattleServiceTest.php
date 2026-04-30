<?php

namespace Tests\Unit;

use App\Services\BattleService;
use PHPUnit\Framework\TestCase;

class BattleServiceTest extends TestCase
{
    private BattleService $battle;

    protected function setUp(): void
    {
        parent::setUp();
        $this->battle = new BattleService();
    }

    /** score(stats) calcula correctamente: hp + attack + defense. */
    public function test_score_calculates_correctly(): void
    {
        // Autor: Rodolfo Huitron
        $pokemon = ['hp' => 35, 'attack' => 55, 'defense' => 40];

        $this->assertSame(130, $this->battle->score($pokemon));
    }

    /** winner(scoreA, scoreB) devuelve A, B o EMPATE correctamente. */
    public function test_winner_returns_correct_result(): void
    {
        // Autor: Andrehi Sandoval
        $this->assertSame('A', $this->battle->winner(200, 100));
        $this->assertSame('B', $this->battle->winner(100, 200));
        $this->assertSame('EMPATE', $this->battle->winner(150, 150));
    }
}
