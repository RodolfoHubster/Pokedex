@extends('layouts.app')

@section('content')
<div class="px-4 py-6 mx-auto max-w-7xl">
    <h2 class="mb-8 text-3xl font-bold tracking-widest text-yellow-500 uppercase">Pokémon</h2>

    <form method="GET" action="/pokemon" class="mb-10">
        <div class="flex max-w-lg gap-0 shadow-lg">
            <input
                type="text"
                name="search"
                class="w-full px-5 py-3 text-white placeholder-gray-400 bg-gray-800 border-y border-l border-gray-700 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent rounded-l-md"
                placeholder="Buscar Pokémon por nombre..."
                value="{{ $query }}"
            >
            <button class="px-6 py-3 font-bold text-gray-900 uppercase transition bg-yellow-500 border border-yellow-500 rounded-r-md hover:bg-yellow-400" type="submit">Buscar</button>
            @if($query)
                <a href="/pokemon" class="flex items-center px-4 ml-3 font-medium text-gray-300 transition bg-gray-700 rounded-md hover:bg-gray-600">Limpiar</a>
            @endif
        </div>
        @if($error)
            <p class="mt-3 text-sm font-bold text-red-400">{{ $error }}</p>
        @endif
    </form>

    @if(count($pokemons) === 0 && !$error)
        <div class="p-5 text-yellow-500 bg-gray-800 border border-gray-700 rounded-md shadow-sm">Cargando base de datos...</div>
    @else
    <div class="grid grid-cols-2 gap-6 md:grid-cols-4 lg:grid-cols-5">
        @foreach($pokemons as $pokemon)
        <div class="flex flex-col items-center h-full p-5 transition-all duration-200 bg-gray-800 border-l-4 border-yellow-500 rounded-lg shadow-md hover:bg-gray-750 hover:shadow-yellow-500/10 hover:-translate-y-1">
            @if($pokemon['sprite'])
                <img src="{{ $pokemon['sprite'] }}" alt="{{ $pokemon['name'] }}" class="w-24 mx-auto drop-shadow-md">
            @endif
            <h5 class="mt-5 text-lg font-bold tracking-wide text-white uppercase">{{ $pokemon['name'] }}</h5>
            <div class="flex w-full gap-2 pt-5 mt-auto">
                <a href="/pokemon/{{ $pokemon['name'] }}" class="flex-1 py-2 text-xs font-bold text-center text-gray-900 uppercase transition bg-yellow-500 rounded hover:bg-yellow-400">Ver</a>
                @auth
                    @if(in_array($pokemon['name'], $favoriteNames))
                        <form method="POST" action="{{ route('favorites.destroy', $pokemon['name']) }}" class="flex">
                            @csrf @method('DELETE')
                            <button class="px-3 py-2 text-sm transition bg-gray-600 rounded hover:bg-gray-500" title="Quitar de favoritos">⭐</button>
                        </form>
                    @else
                        <form method="POST" action="{{ route('favorites.store') }}" class="flex">
                            @csrf
                            <input type="hidden" name="pokemon_name" value="{{ $pokemon['name'] }}">
                            <button class="px-3 py-2 text-sm text-yellow-500 transition border border-yellow-500 rounded hover:bg-gray-700" title="Guardar">☆</button>
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