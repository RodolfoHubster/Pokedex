@extends('layouts.app')

@section('content')
<div class="px-4 py-6 mx-auto max-w-7xl">
    <div class="flex items-center justify-between mb-8">
        <h2 class="text-3xl font-bold tracking-widest text-yellow-500 uppercase">Mis Favoritos</h2>
        <span class="px-4 py-1 text-xs font-bold tracking-widest text-gray-900 uppercase bg-yellow-500 rounded-full shadow-sm">{{ count($pokemons) }} Seleccionados</span>
    </div>

    @if(count($pokemons) === 0)
        <div class="p-10 text-center bg-gray-800 border-l-4 border-yellow-500 rounded-xl shadow-2xl">
            <p class="text-xl font-bold tracking-wide text-yellow-500 uppercase">Aún no tienes Pokémon favoritos.</p>
            <p class="mt-3 text-sm font-medium text-gray-400">Ve al listado general y comienza a armar tu equipo de élite.</p>
            <a href="/pokemon" class="inline-block px-8 py-3 mt-8 font-bold tracking-widest text-gray-900 uppercase transition bg-yellow-500 rounded-md shadow-md hover:bg-yellow-400">Explorar Pokémon</a>
        </div>
    @else
    <div class="grid grid-cols-2 gap-6 md:grid-cols-4 lg:grid-cols-5">
        @foreach($pokemons as $pokemon)
        <div class="relative flex flex-col items-center h-full p-5 transition-all duration-300 bg-gray-800 border border-gray-700 shadow-xl rounded-xl hover:bg-gray-750 hover:shadow-yellow-500/20 hover:-translate-y-2 group overflow-hidden">

            <div class="absolute top-0 right-0 z-20 flex items-center gap-1 px-3 py-1 bg-gradient-to-r from-yellow-600 to-yellow-400 rounded-bl-xl shadow-[0_0_15px_rgba(234,179,8,0.4)]">
                <span class="text-[10px] font-black tracking-widest text-gray-900 uppercase">Top</span>
                <svg class="w-3 h-3 text-gray-900" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
            </div>

            <div class="absolute inset-0 transition-opacity duration-300 opacity-0 pointer-events-none rounded-xl bg-gradient-to-b from-yellow-500/10 to-transparent group-hover:opacity-100"></div>

            @if($pokemon['sprite'])
                <img src="{{ $pokemon['sprite'] }}" alt="{{ $pokemon['name'] }}" class="relative z-10 w-24 mx-auto mt-4 transition-transform duration-300 drop-shadow-[0_5px_10px_rgba(234,179,8,0.25)] group-hover:scale-110">
            @endif

            <h5 class="relative z-10 mt-6 text-lg font-bold tracking-wide text-white uppercase">{{ $pokemon['name'] }}</h5>

            <div class="relative z-10 flex w-full gap-2 pt-5 mt-auto">
                <a href="/pokemon/{{ $pokemon['name'] }}" class="flex-1 py-2 text-xs font-bold tracking-wider text-center text-gray-300 uppercase transition border border-gray-600 rounded hover:bg-gray-700 hover:text-white">Revisar</a>
                
                <form method="POST" action="{{ route('favorites.destroy', $pokemon['name']) }}" class="flex">
                    @csrf @method('DELETE')
                    <button class="px-3 py-2 text-sm text-yellow-500 transition border border-yellow-500 rounded hover:bg-yellow-500 hover:text-gray-900" title="Remover de mi equipo">✕</button>
                </form>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>
@endsection