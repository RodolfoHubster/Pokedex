@extends('layouts.app')

@section('content')
<h2 class="mb-4 fw-bold" style="color:#cc0000">Pokémon</h2>

<div class="row row-cols-2 row-cols-md-4 g-3">
    @foreach($pokemons as $pokemon)
    <div class="col">
        <div class="card h-100 text-center shadow-sm">
            <div class="card-body">
                <h5 class="card-title text-capitalize fw-bold">{{ $pokemon }}</h5>
                <a href="/pokemon/{{ $pokemon }}" class="btn btn-danger btn-sm mt-2">Ver</a>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection