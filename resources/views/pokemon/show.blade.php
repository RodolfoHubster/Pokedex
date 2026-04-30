@extends('layouts.app')

@section('content')
<div class="max-w-4xl px-4 py-12 mx-auto text-center">
    <h1 class="text-5xl font-bold tracking-widest text-yellow-500 uppercase">{{ $pokemon['name'] }}</h1>

    <img src="{{ $pokemon['sprite'] }}" alt="{{ $pokemon['name'] }}" class="w-56 mx-auto my-10 drop-shadow-[0_10px_15px_rgba(234,179,8,0.15)] scale-110">

    <div class="flex justify-center gap-3 mb-10">
        @foreach($pokemon['types'] as $type)
            <span class="px-5 py-2 text-xs font-bold tracking-wider text-gray-900 uppercase bg-gray-300 rounded-full shadow-sm">{{ $type }}</span>
        @endforeach
    </div>

    <div class="max-w-md p-8 mx-auto bg-gray-800 border-l-4 border-yellow-500 shadow-xl rounded-xl">
        <h5 class="mb-8 text-xl font-bold tracking-wide text-gray-300 uppercase">Estadísticas Base</h5>
        <div class="flex justify-between text-center">
            <div>
                <div class="text-4xl font-bold text-yellow-500">{{ $pokemon['hp'] }}</div>
                <small class="text-xs font-bold tracking-widest text-gray-500 uppercase mt-2 block">HP</small>
            </div>
            <div>
                <div class="text-4xl font-bold text-yellow-500">{{ $pokemon['attack'] }}</div>
                <small class="text-xs font-bold tracking-widest text-gray-500 uppercase mt-2 block">Attack</small>
            </div>
            <div>
                <div class="text-4xl font-bold text-yellow-500">{{ $pokemon['defense'] }}</div>
                <small class="text-xs font-bold tracking-widest text-gray-500 uppercase mt-2 block">Defense</small>
            </div>
        </div>
    </div>

    <div class="flex flex-col items-center justify-center gap-4 mt-12 sm:flex-row">
        <a href="/pokemon" class="px-6 py-3 font-bold tracking-wide text-gray-300 uppercase transition bg-gray-700 rounded-lg shadow-sm hover:bg-gray-600">Volver al listado</a>
        @auth
            @if($isFavorite)
                <form method="POST" action="{{ route('favorites.destroy', $pokemon['name']) }}">
                    @csrf @method('DELETE')
                    <button class="px-6 py-3 font-bold tracking-wide text-gray-900 uppercase transition bg-yellow-500 rounded-lg shadow-md hover:bg-yellow-400">⭐ Quitar Favorito</button>
                </form>
            @else
                <form method="POST" action="{{ route('favorites.store') }}">
                    @csrf
                    <input type="hidden" name="pokemon_name" value="{{ $pokemon['name'] }}">
                    <button class="px-6 py-3 font-bold tracking-wide text-yellow-500 uppercase transition border-2 border-yellow-500 rounded-lg hover:bg-gray-800">☆ Guardar Favorito</button>
                </form>
            @endif
        @else
            <a href="{{ route('login') }}" class="px-6 py-3 font-bold tracking-wide text-yellow-500 uppercase transition border-2 border-yellow-500 rounded-lg hover:bg-gray-800">Inicia sesión para guardar</a>
        @endauth
    </div>
</div>
@endsection