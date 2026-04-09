@extends('layouts.app')  {{-- o <x-app-layout> si usas Breeze --}}

@section('content')
<div class="px-4 py-6 mx-auto max-w-7xl">
    {{-- Saludo --}}
    <h2 class="mb-8 text-3xl font-bold tracking-widest text-yellow-500 uppercase">
        Bienvenido, {{ auth()->user()->name }} 👋
    </h2>

    {{-- KPIs --}}
    <div class="grid grid-cols-2 gap-4 mb-10 md:grid-cols-4">
        <div class="p-5 bg-gray-800 border border-gray-700 rounded-xl shadow-xl">
            <p class="text-xs tracking-widest text-gray-400 uppercase">Favoritos</p>
            <p class="mt-2 text-4xl font-black text-yellow-500">{{ $totalFavorites }}</p>
        </div>
        {{-- más KPIs aquí --}}
    </div>

    {{-- Favoritos recientes --}}
    <h3 class="mb-4 text-sm font-bold tracking-widest text-gray-400 uppercase">Últimos agregados</h3>
    <div class="grid grid-cols-2 gap-4 md:grid-cols-5">
        @forelse($recentFavorites as $fav)
        <div class="flex flex-col items-center p-4 bg-gray-800 border border-gray-700 rounded-xl">
            <img src="{{ $fav->sprite_data }}" class="w-20" alt="{{ $fav->pokemon_name }}">
            <p class="mt-2 text-sm font-bold text-white uppercase">{{ $fav->pokemon_name }}</p>
            <a href="/pokemon/{{ $fav->pokemon_name }}" class="mt-3 text-xs text-yellow-500 hover:underline">Ver detalle →</a>
        </div>
        @empty
        <p class="col-span-5 text-gray-400">Aún no tienes favoritos.</p>
        @endforelse
    </div>
</div>
@endsection