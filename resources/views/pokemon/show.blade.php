@extends('layouts.app')

@section('content')
<div class="text-center py-4">
    <h1 class="text-capitalize fw-bold" style="color:#cc0000">{{ $name }}</h1>

    <div class="card mx-auto mt-3 shadow" style="max-width:300px">
        <div class="card-body">
            <div class="bg-light rounded p-4 mb-3" style="font-size:4rem">?</div>
            <p class="text-muted">Imagen disponible próximamente</p>
        </div>
    </div>

    <a href="/pokemon" class="btn btn-secondary mt-3">Volver al listado</a>
</div>
@endsection