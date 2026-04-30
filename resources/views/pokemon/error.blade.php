@extends('layouts.app')

@section('content')
<div class="flex flex-col items-center justify-center py-24 text-center">

    {{-- Pokeball rota --}}
    <div class="text-8xl mb-6 opacity-50" aria-hidden="true">⚫</div>

    <h1 class="text-8xl font-black text-gray-700 mb-4">404</h1>

    <h2 class="text-2xl font-black text-white uppercase tracking-widest mb-3">
        <span class="text-yellow-500 capitalize">{{ $name }}</span> no encontrado
    </h2>

    <p class="text-gray-400 max-w-sm mb-8">
        El Pokémon que buscas no existe o la PokéAPI no respondió. Revisa el nombre e intenta de nuevo.
    </p>

    <a href="/pokemon"
       class="px-8 py-3 font-black text-gray-900 uppercase bg-yellow-500 rounded-xl hover:bg-yellow-400 transition-all duration-200 shadow-lg shadow-yellow-500/30 hover:-translate-y-1">
        ← Volver al listado
    </a>
</div>
@endsection