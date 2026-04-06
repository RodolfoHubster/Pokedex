<?php

use App\Http\Controllers\PokemonController;
use Illuminate\Support\Facades\Route;

// GET / -> Home
Route::get('/', function () {
    return view('home');
});

// GET /pokemon -> Listado
Route::get('/pokemon', [PokemonController::class, 'index']);

// GET /pokemon/{name} -> Detalle
Route::get('/pokemon/{name}', [PokemonController::class, 'show']);
