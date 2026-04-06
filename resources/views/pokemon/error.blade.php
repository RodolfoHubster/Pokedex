@extends('layouts.app')

@section('content')
<div class="text-center py-5">
    <h1 style="font-size:5rem">?</h1>
    <h2 class="fw-bold text-capitalize">{{ $name }} no encontrado</h2>
    <p class="text-muted">El Pokémon que buscas no existe o la API no respondió.</p>
    <a href="/pokemon" class="btn btn-danger mt-3">Volver al listado</a>
</div>
@endsection