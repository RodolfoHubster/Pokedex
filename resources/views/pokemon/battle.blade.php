@extends('layouts.app')

@section('content')
<div class="max-w-4xl px-4 py-10 mx-auto">

    {{-- Header --}}
    <div class="text-center mb-10">
        <p class="text-xs font-bold tracking-widest text-yellow-500 uppercase mb-2"><span aria-hidden="true">⚔️</span> Comparador de Stats</p>
        <h1 class="text-4xl font-black tracking-widest text-white uppercase">
            Pokémon <span class="text-yellow-500">Battle</span>
        </h1>
        <p class="mt-2 text-gray-400 text-sm">Compara HP + Ataque + Defensa para descubrir quién gana</p>
    </div>

    {{-- Formulario --}}
    <form method="GET" action="{{ route('battle') }}" class="mb-10">
        <div class="grid grid-cols-2 gap-4 max-w-lg mx-auto">
            <div>
                <label class="block text-xs font-bold tracking-widest text-gray-400 uppercase mb-2">Pokémon A</label>
                <input type="text" name="A" value="{{ request('A') }}"
                    class="w-full px-4 py-3 text-white bg-gray-800 border border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent"
                    placeholder="Ej: pikachu">
            </div>
            <div>
                <label class="block text-xs font-bold tracking-widest text-gray-400 uppercase mb-2">Pokémon B</label>
                <input type="text" name="B" value="{{ request('B') }}"
                    class="w-full px-4 py-3 text-white bg-gray-800 border border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent"
                    placeholder="Ej: bulbasaur">
            </div>
        </div>
        <div class="text-center mt-5">
            <button type="submit"
                class="px-8 py-3 font-black text-gray-900 uppercase bg-yellow-500 rounded-xl hover:bg-yellow-400 transition-all duration-200 shadow-lg shadow-yellow-500/30 hover:-translate-y-1">
                ⚔️ ¡Combatir!
            </button>
        </div>
    </form>

    {{-- Error --}}
    @if(isset($error) && $error)
        <div class="max-w-lg mx-auto p-5 bg-red-500/10 border border-red-500/30 rounded-xl text-center mb-6">
            <p class="text-red-400 font-bold tracking-wide">{{ $error }}</p>
        </div>
    @endif

    {{-- Resultado --}}
    @if(isset($winner))
    <div class="grid grid-cols-2 gap-6 mb-8">

        {{-- Pokémon A --}}
        <div class="p-6 bg-gray-800 border-2 {{ $winner === 'A' ? 'border-yellow-500 shadow-xl shadow-yellow-500/20' : 'border-gray-700' }} rounded-xl text-center relative overflow-hidden">
            @if($winner === 'A')
                <div class="mb-3 text-yellow-500 font-black tracking-widest uppercase text-xs">🏆 GANADOR</div>
            @endif
            <img src="{{ $pokemonA['sprite'] ?? '' }}" alt="{{ $pokemonA['name'] }}" class="w-32 mx-auto drop-shadow-md">
            <h2 class="text-xl font-black uppercase text-white mt-2">{{ $pokemonA['name'] }}</h2>
            <div class="flex justify-center gap-1 mt-3 flex-wrap">
                @foreach($pokemonA['types'] as $type)
                    <span class="type-{{ $type }} px-3 py-1 text-xs font-bold text-white rounded-full uppercase tracking-wide">{{ $type }}</span>
                @endforeach
            </div>
            <div class="grid grid-cols-3 gap-2 mt-5 text-center">
                <div>
                    <div class="text-2xl font-black text-yellow-500">{{ $pokemonA['hp'] }}</div>
                    <div class="text-xs text-gray-500 uppercase tracking-widest mt-1">HP</div>
                </div>
                <div>
                    <div class="text-2xl font-black text-yellow-500">{{ $pokemonA['attack'] }}</div>
                    <div class="text-xs text-gray-500 uppercase tracking-widest mt-1">ATK</div>
                </div>
                <div>
                    <div class="text-2xl font-black text-yellow-500">{{ $pokemonA['defense'] }}</div>
                    <div class="text-xs text-gray-500 uppercase tracking-widest mt-1">DEF</div>
                </div>
            </div>
            <div class="mt-5 p-3 bg-gray-700/60 rounded-lg">
                <span class="text-gray-400 text-xs uppercase tracking-widest">Score Total</span>
                <div class="text-3xl font-black {{ $winner === 'A' ? 'text-yellow-400' : 'text-white' }}">{{ $scoreA }}</div>
            </div>
        </div>

        {{-- Pokémon B --}}
        <div class="p-6 bg-gray-800 border-2 {{ $winner === 'B' ? 'border-yellow-500 shadow-xl shadow-yellow-500/20' : 'border-gray-700' }} rounded-xl text-center relative overflow-hidden">
            @if($winner === 'B')
                <div class="mb-3 text-yellow-500 font-black tracking-widest uppercase text-xs">🏆 GANADOR</div>
            @endif
            <img src="{{ $pokemonB['sprite'] ?? '' }}" alt="{{ $pokemonB['name'] }}" class="w-32 mx-auto drop-shadow-md">
            <h2 class="text-xl font-black uppercase text-white mt-2">{{ $pokemonB['name'] }}</h2>
            <div class="flex justify-center gap-1 mt-3 flex-wrap">
                @foreach($pokemonB['types'] as $type)
                    <span class="type-{{ $type }} px-3 py-1 text-xs font-bold text-white rounded-full uppercase tracking-wide">{{ $type }}</span>
                @endforeach
            </div>
            <div class="grid grid-cols-3 gap-2 mt-5 text-center">
                <div>
                    <div class="text-2xl font-black text-yellow-500">{{ $pokemonB['hp'] }}</div>
                    <div class="text-xs text-gray-500 uppercase tracking-widest mt-1">HP</div>
                </div>
                <div>
                    <div class="text-2xl font-black text-yellow-500">{{ $pokemonB['attack'] }}</div>
                    <div class="text-xs text-gray-500 uppercase tracking-widest mt-1">ATK</div>
                </div>
                <div>
                    <div class="text-2xl font-black text-yellow-500">{{ $pokemonB['defense'] }}</div>
                    <div class="text-xs text-gray-500 uppercase tracking-widest mt-1">DEF</div>
                </div>
            </div>
            <div class="mt-5 p-3 bg-gray-700/60 rounded-lg">
                <span class="text-gray-400 text-xs uppercase tracking-widest">Score Total</span>
                <div class="text-3xl font-black {{ $winner === 'B' ? 'text-yellow-400' : 'text-white' }}">{{ $scoreB }}</div>
            </div>
        </div>
    </div>

    {{-- Banner ganador --}}
    <div class="text-center p-8 bg-gray-800 border border-yellow-500/30 rounded-xl shadow-xl">
        @if($winner === 'EMPATE')
            <div class="text-5xl mb-3">🤝</div>
            <h2 class="text-3xl font-black text-white uppercase tracking-widest">¡Empate!</h2>
            <p class="text-gray-400 mt-2">Ambos Pokémon tienen el mismo score: <span class="text-yellow-500 font-bold">{{ $scoreA }}</span></p>
        @else
            <div class="text-5xl mb-3">🏆</div>
            <h2 class="text-3xl font-black text-yellow-500 uppercase tracking-widest">
                ¡{{ $winner === 'A' ? strtoupper($pokemonA['name']) : strtoupper($pokemonB['name']) }} gana!
            </h2>
            <p class="text-gray-400 mt-2">
                Score: <span class="text-yellow-500 font-bold">{{ $winner === 'A' ? $scoreA : $scoreB }}</span>
                vs <span class="text-gray-500">{{ $winner === 'A' ? $scoreB : $scoreA }}</span>
            </p>
        @endif
    </div>
    @endif

</div>
@endsection
