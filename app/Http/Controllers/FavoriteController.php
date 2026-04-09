<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class FavoriteController extends Controller
{
    public function index()
    {
        $favorites = auth()->user()->favorites()->orderBy('pokemon_name')->get();

        $pokemons = $favorites->map(function ($fav) {
            $response = Http::withoutVerifying()->withUserAgent('Pokedex/1.0')
                ->get("https://pokeapi.co/api/v2/pokemon/{$fav->pokemon_name}");

            if ($response->successful()) {
                $data = $response->json();
                return [
                    'name'   => $data['name'],
                    'sprite' => $data['sprites']['front_default'],
                ];
            }

            return ['name' => $fav->pokemon_name, 'sprite' => null];
        });

        return view('pokemon.favorites', compact('pokemons'));
    }

    public function store(Request $request)
    {
        $request->validate(['pokemon_name' => 'required|string']);

        auth()->user()->favorites()->firstOrCreate([
            'pokemon_name' => $request->pokemon_name,
        ]);

        return back();
    }

    public function destroy($name)
    {
        auth()->user()->favorites()->where('pokemon_name', $name)->delete();

        return back();
    }
}
