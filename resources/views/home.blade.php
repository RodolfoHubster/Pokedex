@extends('layouts.app')

@section('content')
<div class="text-center py-5">

    <h1 class="display-4 fw-bold" style="color:#cc0000">
        Bienvenido a la Chachil's Pokédex
    </h1>

    <p class="lead text-muted mt-3">
        Explora el mundo Pokémon construido con Laravel y la PokéAPI
    </p>

    <a href="/pokemon" class="btn btn-danger btn-lg mt-4">
        Ver el listado de Pokémones
    </a>

</div>
@endsection