<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PokemonController extends Controller
{
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
        $response = Http::withoutVerifying()->withUserAgent('Pokedex/1.0')->get("https://pokeapi.co/api/v2/pokemon/{$query}");
        if ($response->successful()) {
            $data = $response->json();
            $pokemons = [[
                'name'   => $data['name'],
                'sprite' => $data['sprites']['front_default'],
            ]];
        } else {
            $error = 'No se encontró ningún Pokémon con ese nombre.';
        }
    } else {
        $response = Http::withoutVerifying()->withUserAgent('Pokedex/1.0')->get('https://pokeapi.co/api/v2/pokemon?limit=20');
        if ($response->successful()) {
            $list = $response->json()['results'];
            foreach ($list as $item) {
                $detail = Http::withoutVerifying()->withUserAgent('Pokedex/1.0')->get($item['url'])->json();
                $pokemons[] = [
                    'name'   => $item['name'],
                    'sprite' => $detail['sprites']['front_default'],
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
    $response = Http::withoutVerifying()->withUserAgent('Pokedex/1.0')->get("https://pokeapi.co/api/v2/pokemon/{$name}");

    if (!$response->successful()) {
        return view('pokemon.error', compact('name'));
    }

    $data    = $response->json();
    $pokemon = [
        'name'    => $data['name'],
        'sprite'  => $data['sprites']['front_default'],
        'types'   => array_map(fn($t) => $t['type']['name'], $data['types']),
        'hp'      => $data['stats'][0]['base_stat'],
        'attack'  => $data['stats'][1]['base_stat'],
        'defense' => $data['stats'][2]['base_stat'],
    ];

    $isFavorite = auth()->check() &&
        auth()->user()->favorites()->where('pokemon_name', $data['name'])->exists();

    return view('pokemon.show', compact('pokemon', 'isFavorite'));
}

    public function about()
    {
        return view('about');
    }
}