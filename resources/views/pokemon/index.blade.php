@extends('layouts.app')

@section('content')
<div class="px-4 py-6 mx-auto max-w-7xl">

    <div class="flex items-center justify-between mb-8">
        <div class="flex items-center gap-3">
            <h2 class="text-3xl font-black tracking-widest text-yellow-500 uppercase">Pokémon</h2>
            @if($type)
                <span class="type-{{ $type }} px-3 py-1 text-xs font-bold text-white rounded-full uppercase tracking-wide">
                    {{ $type }}
                </span>
                <a href="/pokemon" class="text-xs text-gray-500 hover:text-red-400 transition-colors">✕ Limpiar</a>
            @endif
        </div>
        <span class="text-xs text-gray-500 tracking-widest uppercase">{{ count($pokemons) }} resultados</span>
    </div>

    {{-- Buscador --}}
    <form method="GET" action="/pokemon" class="mb-10">
        <div class="flex max-w-lg gap-0 shadow-lg">
            <input
                type="text"
                name="search"
                class="w-full px-5 py-3 text-white placeholder-gray-500 bg-gray-800 border border-gray-700 rounded-l-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent"
                placeholder="Buscar por nombre..."
                value="{{ $query }}"
            >
            <button class="px-6 py-3 font-black text-gray-900 uppercase bg-yellow-500 border border-yellow-500 rounded-r-lg hover:bg-yellow-400 transition-colors" type="submit">
                Buscar
            </button>
            @if($query)
                <a href="/pokemon" class="flex items-center px-4 ml-2 text-sm font-medium text-gray-300 bg-gray-700 rounded-lg hover:bg-gray-600 transition-colors">✕</a>
            @endif
        </div>
        @if($error)
            <p class="mt-3 text-sm font-bold text-red-400">{{ $error }}</p>
        @endif
    </form>

    @if(count($pokemons) === 0 && !$error)
        <div class="p-8 text-center bg-gray-800 border border-gray-700 rounded-xl">
            <p class="text-yellow-500 font-bold tracking-widest uppercase">Cargando Pokédex...</p>
        </div>
    @else
    <div class="grid grid-cols-2 gap-5 md:grid-cols-4 lg:grid-cols-5">
        @foreach($pokemons as $pokemon)
        <div class="group flex flex-col items-center p-5 bg-gray-800 border border-gray-700 rounded-xl shadow-md hover:-translate-y-2 hover:shadow-yellow-500/20 hover:shadow-xl hover:border-yellow-500/50 transition-all duration-300 cursor-pointer">

            {{-- Número --}}
            <span class="self-end text-xs text-gray-600 font-mono mb-1">
                #{{ str_pad($loop->iteration, 3, '0', STR_PAD_LEFT) }}
            </span>

            {{-- Sprite --}}
            @if($pokemon['sprite'])
                <img src="{{ $pokemon['sprite'] }}"
                     alt="{{ $pokemon['name'] }}"
                     class="w-24 mx-auto drop-shadow-md group-hover:scale-110 transition-transform duration-300">
            @else
                <div class="w-24 h-24 flex items-center justify-center text-gray-600 text-4xl">?</div>
            @endif

            {{-- Nombre --}}
            <h5 class="mt-3 text-sm font-black tracking-wide text-white uppercase text-center">
                {{ $pokemon['name'] }}
            </h5>

            {{-- Botones --}}
            <div class="flex w-full gap-2 pt-4 mt-auto">
                <a href="/pokemon/{{ $pokemon['name'] }}"
                   class="flex-1 py-2 text-xs font-bold text-center text-gray-900 uppercase bg-yellow-500 rounded-lg hover:bg-yellow-400 transition-colors">
                    Ver
                </a>
                @auth
                    @if(in_array($pokemon['name'], $favoriteNames))
                        <form method="POST" action="{{ route('favorites.destroy', $pokemon['name']) }}">
                            @csrf @method('DELETE')
                            <button class="px-3 py-2 text-sm bg-yellow-500/20 border border-yellow-500/50 rounded-lg hover:bg-red-500/20 hover:border-red-500/50 transition-colors" title="Quitar favorito"><i class="fa-solid fa-star text-yellow-400"></i></button>
                        </form>
                    @else
                        <form method="POST" action="{{ route('favorites.store') }}">
                            @csrf
                            <input type="hidden" name="pokemon_name" value="{{ $pokemon['name'] }}">
                            <button class="px-3 py-2 text-sm border border-gray-600 rounded-lg hover:border-yellow-500/50 hover:bg-yellow-500/10 transition-colors" title="Agregar favorito"><i class="fa-regular fa-star text-gray-400"></i></button>
                        </form>
                    @endif
                @endauth
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>
@endsection