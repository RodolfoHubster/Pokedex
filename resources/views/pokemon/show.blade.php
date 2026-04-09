@extends('layouts.app')

@section('content')
<div class="text-center py-4">
    <h1 class="text-capitalize fw-bold" style="color:#cc0000">{{ $pokemon['name'] }}</h1>

    <img src="{{ $pokemon['sprite'] }}" alt="{{ $pokemon['name'] }}" width="160" class="my-3">

    <div class="d-flex justify-content-center gap-2 mb-3">
        @foreach($pokemon['types'] as $type)
            <span class="badge bg-danger text-capitalize fs-6">{{ $type }}</span>
        @endforeach
    </div>

    <div class="card mx-auto shadow" style="max-width:320px">
        <div class="card-body">
            <h5 class="fw-bold mb-3">Stats</h5>
            <div class="d-flex justify-content-around">
                <div>
                    <div class="fw-bold fs-4" style="color:#cc0000">{{ $pokemon['hp'] }}</div>
                    <small class="text-muted">HP</small>
                </div>
                <div>
                    <div class="fw-bold fs-4" style="color:#cc0000">{{ $pokemon['attack'] }}</div>
                    <small class="text-muted">Attack</small>
                </div>
                <div>
                    <div class="fw-bold fs-4" style="color:#cc0000">{{ $pokemon['defense'] }}</div>
                    <small class="text-muted">Defense</small>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-4 d-flex justify-content-center gap-2">
        <a href="/pokemon" class="btn btn-secondary">Volver al listado</a>
        @auth
            @if($isFavorite)
                <form method="POST" action="{{ route('favorites.destroy', $pokemon['name']) }}">
                    @csrf @method('DELETE')
                    <button class="btn btn-warning">⭐ Quitar de favoritos</button>
                </form>
            @else
                <form method="POST" action="{{ route('favorites.store') }}">
                    @csrf
                    <input type="hidden" name="pokemon_name" value="{{ $pokemon['name'] }}">
                    <button class="btn btn-outline-warning">☆ Guardar en favoritos</button>
                </form>
            @endif
        @else
            <a href="{{ route('login') }}" class="btn btn-outline-warning">☆ Inicia sesión para guardar</a>
        @endauth
    </div>
</div>
@endsection