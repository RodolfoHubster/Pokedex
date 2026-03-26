<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PokemonController;

// GET / -> Home
Route::get('/', function () {
    return view('home');
});

// GET /pokemon -> Listado
Route::get('/pokemon', [PokemonController::class, 'index']);

// GET /pokemon/{name} -> Detalle
Route::get('/pokemon/{name}', [PokemonController::class, 'show']);