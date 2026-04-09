<?php

use App\Http\Controllers\PokemonController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// GET / -> Home
Route::get('/', [PokemonController::class, 'home']);

// GET /pokemon -> Listado
Route::get('/pokemon', [PokemonController::class, 'index']);

// GET /pokemon/{name} -> Detalle
Route::get('/pokemon/{name}', [PokemonController::class, 'show']);

// GET /about -> Acerca de
Route::get('/about', [PokemonController::class, 'about']);

// Favoritos (requiere login)
Route::middleware('auth')->group(function () {
    Route::get('/mis-pokemon', [FavoriteController::class, 'index'])->name('favorites.index');
    Route::post('/favorites', [FavoriteController::class, 'store'])->name('favorites.store');
    Route::delete('/favorites/{name}', [FavoriteController::class, 'destroy'])->name('favorites.destroy');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
