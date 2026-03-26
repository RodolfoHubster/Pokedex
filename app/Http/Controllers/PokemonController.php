<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PokemonController extends Controller
{
    // Método para el Listado
    public function index()
    {
        // Arreglo dummy con 12 pokémon (solo nombres)
        $pokemons = [
            'Bulbasaur', 'Ivysaur', 'Venusaur', 
            'Charmander', 'Charmeleon', 'Charizard', 
            'Squirtle', 'Wartortle', 'Blastoise', 
            'Pikachu', 'Raichu', 'Jigglypuff'
        ];

        return view('pokemon.index', compact('pokemons'));
    }

    // Método para el Detalle
    public function show($name)
    {
        return view('pokemon.show', compact('name'));
    }
}