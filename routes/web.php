<?php

use App\Http\Controllers\PokemonController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Públicas
Route::get('/', [PokemonController::class, 'home']);
Route::get('/pokemon', [PokemonController::class, 'index']);
Route::get('/pokemon/{name}', [PokemonController::class, 'show']);
Route::get('/about', [PokemonController::class, 'about']);

// Protegidas (requieren login)
Route::middleware('auth')->group(function () {

    Route::get('/dashboard', function () {
        $user = auth()->user();
        $favorites = $user->favorites()->latest()->get();

        return view('dashboard', [
            'totalFavorites'  => $favorites->count(),
            'recentFavorites' => $favorites->take(5),
            'lastAdded'       => $favorites->first(),
        ]);
    })->name('dashboard');

    Route::get('/mis-pokemon', [FavoriteController::class, 'index'])->name('favorites.index');
    Route::post('/favorites', [FavoriteController::class, 'store'])->name('favorites.store');
    Route::delete('/favorites/{name}', [FavoriteController::class, 'destroy'])->name('favorites.destroy');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ⬇️ ESTA LÍNEA es la que carga login, register, logout, etc.
require __DIR__.'/auth.php';