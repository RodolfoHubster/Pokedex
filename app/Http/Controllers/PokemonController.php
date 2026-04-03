<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PokemonController extends Controller
{
    protected $pokemons = [
        'bulbasaur', 'charmander', 'squirtle', 'pikachu',
        'jigglypuff', 'meowth', 'psyduck', 'gengar',
        'eevee', 'snorlax', 'mewtwo', 'dragonite'
    ];

    public function home()
    {
        return view('home');
    }

    public function index(Request $request)
    {
        $pokemons = $this->pokemons;
        return view('pokemon.index', compact('pokemons'));
    }

    public function show($name)
    {
        return view('pokemon.show', compact('name'));
    }

    public function about()
    {
        return view('about');
    }
}