@extends('layouts.app')

@section('content')
<div class="px-4 py-6 mx-auto max-w-7xl">

    <h2 class="mb-8 text-3xl font-bold tracking-widest text-yellow-500 uppercase">
        Bienvenido, {{ auth()->user()->name }} 👋
    </h2>

    {{-- KPIs --}}
    <div class="grid grid-cols-2 gap-4 mb-10 md:grid-cols-3">
        <div class="p-5 bg-gray-800 border border-gray-700 rounded-xl shadow-xl">
            <p class="text-xs tracking-widest text-gray-400 uppercase">Total Favoritos</p>
            <p class="mt-2 text-4xl font-black text-yellow-500">{{ $totalFavorites }}</p>
        </div>
        <div class="p-5 bg-gray-800 border border-gray-700 rounded-xl shadow-xl">
            <p class="text-xs tracking-widest text-gray-400 uppercase">Último agregado</p>
            <p class="mt-2 text-lg font-black text-white uppercase">
                {{ $lastAdded ? $lastAdded->pokemon_name : '—' }}
            </p>
        </div>
        <div class="p-5 bg-gray-800 border border-gray-700 rounded-xl shadow-xl">
            <p class="text-xs tracking-widest text-gray-400 uppercase">Miembro desde</p>
            <p class="mt-2 text-lg font-black text-white">
                {{ auth()->user()->created_at->format('d/m/Y') }}
            </p>
        </div>
    </div>

    {{-- Accesos rápidos --}}
    <div class="flex gap-4 mb-10">
        <a href="/pokemon" class="px-6 py-3 text-sm font-bold tracking-widest text-gray-900 uppercase bg-yellow-500 rounded-lg hover:bg-yellow-400 transition-colors duration-150">
            Explorar Pokédex
        </a>
        <a href="{{ route('favorites.index') }}" class="px-6 py-3 text-sm font-bold tracking-widest text-gray-300 uppercase border border-gray-600 rounded-lg hover:border-yellow-500 hover:text-yellow-500 transition-colors duration-150">
            Mi equipo completo
        </a>
    </div>

    {{-- Favoritos recientes --}}
    <h3 class="mb-4 text-sm font-bold tracking-widest text-gray-400 uppercase">Últimos agregados</h3>
    <div class="grid grid-cols-2 gap-4 md:grid-cols-5">
        @forelse($recentFavorites as $fav)
        <div class="flex flex-col items-center p-4 bg-gray-800 border border-gray-700 rounded-xl hover:-translate-y-1 hover:shadow-yellow-500/20 hover:shadow-xl transition-all duration-300">
            <img src="{{ $fav->sprite_data }}" class="w-20" alt="{{ $fav->pokemon_name }}">
            <p class="mt-2 text-sm font-bold text-white uppercase">{{ $fav->pokemon_name }}</p>
            <a href="/pokemon/{{ $fav->pokemon_name }}" class="mt-3 text-xs text-yellow-500 hover:underline">Ver detalle →</a>
        </div>
        @empty
        <div class="col-span-5 p-10 text-center bg-gray-800 border border-gray-700 rounded-xl">
            <p class="text-gray-400 uppercase tracking-widest text-sm">Aún no tienes favoritos</p>
            <a href="/pokemon" class="inline-block mt-4 px-6 py-2 text-xs font-bold tracking-widest text-gray-900 uppercase bg-yellow-500 rounded-md hover:bg-yellow-400">
                Explorar Pokémon
            </a>
        </div>
        @endforelse
    </div>

</div>
@endsection