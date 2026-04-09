@extends('layouts.app')

@section('content')
<h2 class="mb-4 fw-bold" style="color:#cc0000">⭐ Mis Pokémon</h2>

@if($pokemons->isEmpty())
    <div class="alert alert-info">
        No tienes Pokémon guardados todavía.
        <a href="/pokemon" class="alert-link">Explorar Pokémon</a>
    </div>
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
                    <form method="POST" action="{{ route('favorites.destroy', $pokemon['name']) }}">
                        @csrf @method('DELETE')
                        <button class="btn btn-warning btn-sm" title="Quitar de favoritos">⭐ Quitar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endif
@endsection
