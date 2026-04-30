<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Favorite;

class FavoriteController extends Controller
{
    public function index()
    {
        // CERO peticiones a la API. Todo se lee de tu almacenamiento local.
        $favorites = auth()->user()->favorites()->orderBy('pokemon_name')->get();

        $pokemons = $favorites->map(function ($fav) {
            return [
                'name'   => $fav->pokemon_name,
                'sprite' => $fav->sprite_data, // La imagen ya viene desde tu SQLite
            ];
        });

        return view('pokemon.favorites', compact('pokemons'));
    }

    public function store(Request $request)
    {
        $request->validate(['pokemon_name' => 'required|string']);

        // 1. Evitar duplicados
        if (auth()->user()->favorites()->where('pokemon_name', $request->pokemon_name)->exists()) {
            return back()->with('status', 'Este Pokémon ya está en tu equipo.');
        }

        // 2. Extraer datos y descargar la imagen a formato local (Base64)
        $spriteData = null;
        try {
            $response = Http::withoutVerifying()
                ->withUserAgent('Pokedex/1.0')
                ->timeout(5)
                ->get("https://pokeapi.co/api/v2/pokemon/{$request->pokemon_name}");
            
            if ($response->successful()) {
                $imageUrl = $response->json()['sprites']['front_default'];
                if ($imageUrl) {
                    $imageContent = Http::withoutVerifying()->get($imageUrl)->body();
                    $spriteData = 'data:image/png;base64,' . base64_encode($imageContent);
                }
            }
        } catch (\Exception $e) {
            // Falla silenciosa si no hay internet al momento exacto de querer guardar uno nuevo
        }

        // 3. Persistir en la base de datos local
        auth()->user()->favorites()->create([
            'pokemon_name' => $request->pokemon_name,
            'sprite_data'  => $spriteData,
        ]);

        return back()->with('status', 'Pokémon respaldado localmente en favoritos.');
    }

    public function destroy($name)
    {
        auth()->user()->favorites()->where('pokemon_name', $name)->delete();
        return back()->with('status', 'Pokémon eliminado de favoritos.');
    }
}