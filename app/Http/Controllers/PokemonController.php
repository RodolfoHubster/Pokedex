<?php

namespace App\Http\Controllers;

use App\Services\PokeApiService;
use App\Services\PokemonMapper;
use Illuminate\Http\Request;

class PokemonController extends Controller
{
    public function __construct(
        private PokeApiService $api,
        private PokemonMapper $mapper
    ) {}

    public function home()
    {
        return view('home');
    }

    public function index(Request $request)
    {
        $query = $request->input('search', '');
        $error = null;
        $pokemons = [];

        if ($request->has('search') && trim($query) === '') {
            $error = 'El campo de búsqueda no puede estar vacío.';
        } elseif ($query) {
            $data = $this->api->getPokemon($query);

            if ($data) {
                $pokemons = [[
                    'name'   => $data['name'],
                    'sprite' => $data['sprites']['front_default'] ?? null,
                ]];
            } else {
                $error = 'No se encontró ningún Pokémon con ese nombre.';
            }
        } else {
            $results = $this->api->getPokemonList();

            if (empty($results)) {
                $error = 'Sin conexión a internet. Verifica tu red para buscar nuevos Pokémon.';
            }

            foreach ($results as $item) {
                $detail = $this->api->getPokemon($item['name']);
                if ($detail) {
                    $pokemons[] = [
                        'name'   => $item['name'],
                        'sprite' => $detail['sprites']['front_default'] ?? null,
                    ];
                }
            }
        }

        $favoriteNames = auth()->check()
            ? auth()->user()->favorites()->pluck('pokemon_name')->toArray()
            : [];

        return view('pokemon.index', compact('pokemons', 'query', 'error', 'favoriteNames'));
    }

    public function show($name)
    {
        $data = $this->api->getPokemon($name);

        if (! $data) {
            return view('pokemon.error', compact('name'));
        }

        $pokemon = $this->mapper->map($data);

        $isFavorite = auth()->check() &&
            auth()->user()->favorites()->where('pokemon_name', $pokemon['name'])->exists();

        return view('pokemon.show', compact('pokemon', 'isFavorite'));
    }

    public function about()
    {
        return view('about');
    }
}