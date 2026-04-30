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

        $pokemonA = $this->mapper->map($dataA);
        $pokemonB = $this->mapper->map($dataB);

        $scoreA = $this->battle->score($pokemonA);
        $scoreB = $this->battle->score($pokemonB);
        $winner = $this->battle->winner($scoreA, $scoreB);

        return view('pokemon.battle', compact('pokemonA', 'pokemonB', 'scoreA', 'scoreB', 'winner'));
    }
}
