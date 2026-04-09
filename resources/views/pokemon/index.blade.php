@extends('layouts.app')

@section('content')
<h2 class="mb-4 fw-bold" style="color:#cc0000">Pokémon</h2>

<form method="GET" action="/pokemon" class="mb-4">
    <div class="input-group" style="max-width:400px">
        <input
            type="text"
            name="search"
            class="form-control {{ $error ? 'is-invalid' : '' }}"
            placeholder="Buscar Pokémon por nombre..."
            value="{{ $query }}"
        >
        <button class="btn btn-danger" type="submit">Buscar</button>
        @if($query)
            <a href="/pokemon" class="btn btn-outline-secondary">Limpiar</a>
        @endif
    </div>
    @if($error)
        <div class="text-danger mt-2 small fw-bold">{{ $error }}</div>
    @endif
</form>

@if(count($pokemons) === 0 && !$error)
    <div class="alert alert-info">Cargando Pokémon...</div>
@else
<div class="row row-cols-2 row-cols-md-4 g-3">
    @foreach($pokemons as $pokemon)
    <div class="col">
        <div class="card h-100 text-center shadow-sm">
            <div class="card-body d-flex flex-column">
                @if($pokemon['sprite'])
                    <img src="{{ $pokemon['sprite'] }}" alt="{{ $pokemon['name'] }}" width="80" class="mx-auto">
                @endif
                <h5 class="card-title text-capitalize fw-bold mt-2">{{ $pokemon['name'] }}</h5>
                <div class="mt-auto d-flex gap-1 justify-content-center">
                    <a href="/pokemon/{{ $pokemon['name'] }}" class="btn btn-danger btn-sm">Ver</a>
                    @auth
                        @if(in_array($pokemon['name'], $favoriteNames))
                            <form method="POST" action="{{ route('favorites.destroy', $pokemon['name']) }}">
                                @csrf @method('DELETE')
                                <button class="btn btn-warning btn-sm" title="Quitar de favoritos">⭐</button>
                            </form>
                        @else
                            <form method="POST" action="{{ route('favorites.store') }}">
                                @csrf
                                <input type="hidden" name="pokemon_name" value="{{ $pokemon['name'] }}">
                                <button class="btn btn-outline-warning btn-sm" title="Guardar">☆</button>
                            </form>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endif
@endsection