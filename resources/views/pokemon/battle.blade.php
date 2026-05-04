@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">

    {{-- HEADER --}}
    <div class="text-center mb-8">
        <p class="text-xs font-bold tracking-widest text-yellow-500 uppercase mb-1">
            <i class="fa-solid fa-bolt mr-1"></i> Modo Batalla
        </p>
        <h1 class="text-4xl font-black tracking-widest text-white uppercase">
            Pokémon <span class="text-yellow-500">Battle</span>
        </h1>
        <p class="mt-2 text-xs text-gray-500 tracking-widest uppercase">
            Score = HP + Ataque + Defensa · El mayor puntaje gana
        </p>
    </div>

    {{-- FORMULARIO --}}
    <form method="GET" action="{{ route('battle') }}" class="mb-8">
        <div class="grid grid-cols-2 gap-4 max-w-xl mx-auto">
            <div>
                <label class="block text-xs font-bold tracking-widest text-gray-400 uppercase mb-2">
                    Pokémon A
                </label>
                <input type="text" name="A" value="{{ request('A') }}" autofocus
                    class="w-full px-4 py-3 text-white bg-gray-800 border border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500"
                    placeholder="Ej: pikachu">
            </div>
            <div>
                <label class="block text-xs font-bold tracking-widest text-gray-400 uppercase mb-2">
                    Pokémon B
                </label>
                <input type="text" name="B" value="{{ request('B') }}"
                    class="w-full px-4 py-3 text-white bg-gray-800 border border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500"
                    placeholder="Ej: bulbasaur">
            </div>
        </div>
        <div class="text-center mt-5">
            <button type="submit"
                class="px-10 py-3 font-black text-gray-900 uppercase bg-yellow-500 rounded-xl hover:bg-yellow-400 transition-all duration-200 shadow-lg hover:-translate-y-1">
                <i class="fa-solid fa-bolt mr-2"></i> ¡Comparar!
            </button>
        </div>
    </form>

    {{-- ERROR --}}
    @if(isset($error) && $error)
        <div class="p-5 bg-red-500/10 border border-red-500/30 rounded-xl text-center mb-6 max-w-xl mx-auto">
            <i class="fa-solid fa-circle-exclamation text-red-400 text-2xl mb-2 block"></i>
            <p class="text-red-400 font-bold">{{ $error }}</p>
        </div>
    @endif

    {{-- RESULTADO --}}
    @if(isset($pokemonA) && isset($pokemonB))

    {{-- BANNER GANADOR --}}
    <div class="text-center mb-6">
        @if($winner === 'EMPATE')
            <div class="inline-flex items-center gap-3 px-8 py-3 bg-yellow-500/10 border border-yellow-500/40 rounded-2xl shadow-lg">
                <i class="fa-solid fa-equals text-yellow-500 text-2xl"></i>
                <span class="text-2xl font-black tracking-widest text-yellow-500 uppercase">¡Empate!</span>
                <i class="fa-solid fa-equals text-yellow-500 text-2xl"></i>
            </div>
        @elseif($winner === 'A')
            <div class="inline-flex items-center gap-3 px-8 py-3 bg-green-500/10 border border-green-500/40 rounded-2xl shadow-lg">
                <i class="fa-solid fa-trophy text-yellow-500 text-2xl"></i>
                <span class="text-2xl font-black tracking-widest text-green-400 uppercase">
                    ¡{{ $pokemonA['name'] }} gana!
                </span>
                <i class="fa-solid fa-trophy text-yellow-500 text-2xl"></i>
            </div>
        @else
            <div class="inline-flex items-center gap-3 px-8 py-3 bg-green-500/10 border border-green-500/40 rounded-2xl shadow-lg">
                <i class="fa-solid fa-trophy text-yellow-500 text-2xl"></i>
                <span class="text-2xl font-black tracking-widest text-green-400 uppercase">
                    ¡{{ $pokemonB['name'] }} gana!
                </span>
                <i class="fa-solid fa-trophy text-yellow-500 text-2xl"></i>
            </div>
        @endif
        <p class="mt-2 text-xs text-gray-500 tracking-widest">
            Score A: <span class="text-white font-bold">{{ $scoreA }}</span>
            &nbsp;·&nbsp;
            Score B: <span class="text-white font-bold">{{ $scoreB }}</span>
        </p>
    </div>

    {{-- TARJETAS --}}
    <div class="grid grid-cols-2 gap-6">

        {{-- POKÉMON A --}}
        @php $isWinnerA = $winner === 'A'; $isDrawA = $winner === 'EMPATE'; @endphp
        <div class="relative bg-gray-800 border rounded-2xl overflow-hidden shadow-xl
            {{ $isWinnerA ? 'border-green-500 shadow-green-500/20' : ($isDrawA ? 'border-yellow-500/50' : 'border-gray-700 opacity-75') }}">

            @if($isWinnerA)
                <div class="absolute top-3 right-3 z-10">
                    <span class="px-3 py-1 text-xs font-black text-gray-900 bg-yellow-500 rounded-full uppercase tracking-widest">
                        <i class="fa-solid fa-trophy mr-1"></i> Ganador
                    </span>
                </div>
            @endif

            {{-- Sprite --}}
            <div class="flex justify-center bg-gray-900/50 py-6">
                <img src="{{ $pokemonA['sprite'] }}" alt="{{ $pokemonA['name'] }}"
                     class="w-36 h-36 object-contain drop-shadow-xl {{ $isWinnerA ? 'scale-110' : '' }}">
            </div>

            {{-- Info --}}
            <div class="p-5">
                <h2 class="text-xl font-black tracking-widest text-white uppercase text-center mb-2">
                    {{ $pokemonA['name'] }}
                </h2>
                <div class="flex justify-center gap-2 mb-4">
                    @foreach($pokemonA['types'] as $t)
                        <span class="type-{{ $t }} px-3 py-0.5 text-xs font-bold text-white rounded-full uppercase tracking-wide">{{ $t }}</span>
                    @endforeach
                </div>

                {{-- Stats --}}
                <div class="space-y-2 mb-4">
                    @foreach(['hp' => ['HP', 'fa-heart'], 'attack' => ['Ataque', 'fa-hand-fist'], 'defense' => ['Defensa', 'fa-shield-halved']] as $key => [$label, $icon])
                    <div class="flex items-center gap-3">
                        <div class="w-6 text-center">
                            <i class="fa-solid {{ $icon }} text-gray-500 text-xs"></i>
                        </div>
                        <span class="text-xs text-gray-400 w-16">{{ $label }}</span>
                        <div class="flex-1 h-2 bg-gray-700 rounded-full overflow-hidden">
                            <div class="h-full rounded-full {{ $isWinnerA ? 'bg-green-500' : 'bg-gray-500' }}"
                                 style="width: {{ min(100, round($pokemonA[$key] / 255 * 100)) }}%"></div>
                        </div>
                        <span class="text-xs font-bold text-white w-8 text-right">{{ $pokemonA[$key] }}</span>
                    </div>
                    @endforeach
                </div>

                {{-- Score --}}
                <div class="border-t border-gray-700 pt-3 text-center">
                    <p class="text-xs text-gray-500 uppercase tracking-widest mb-1">Score Total</p>
                    <p class="text-3xl font-black {{ $isWinnerA ? 'text-green-400' : ($isDrawA ? 'text-yellow-500' : 'text-gray-400') }}">
                        {{ $scoreA }}
                    </p>
                    <p class="text-xs text-gray-600 mt-1">{{ $pokemonA['hp'] }} + {{ $pokemonA['attack'] }} + {{ $pokemonA['defense'] }}</p>
                </div>
            </div>
        </div>

        {{-- POKÉMON B --}}
        @php $isWinnerB = $winner === 'B'; @endphp
        <div class="relative bg-gray-800 border rounded-2xl overflow-hidden shadow-xl
            {{ $isWinnerB ? 'border-green-500 shadow-green-500/20' : ($isDrawA ? 'border-yellow-500/50' : 'border-gray-700 opacity-75') }}">

            @if($isWinnerB)
                <div class="absolute top-3 right-3 z-10">
                    <span class="px-3 py-1 text-xs font-black text-gray-900 bg-yellow-500 rounded-full uppercase tracking-widest">
                        <i class="fa-solid fa-trophy mr-1"></i> Ganador
                    </span>
                </div>
            @endif

            {{-- Sprite --}}
            <div class="flex justify-center bg-gray-900/50 py-6">
                <img src="{{ $pokemonB['sprite'] }}" alt="{{ $pokemonB['name'] }}"
                     class="w-36 h-36 object-contain drop-shadow-xl {{ $isWinnerB ? 'scale-110' : '' }}">
            </div>

            {{-- Info --}}
            <div class="p-5">
                <h2 class="text-xl font-black tracking-widest text-white uppercase text-center mb-2">
                    {{ $pokemonB['name'] }}
                </h2>
                <div class="flex justify-center gap-2 mb-4">
                    @foreach($pokemonB['types'] as $t)
                        <span class="type-{{ $t }} px-3 py-0.5 text-xs font-bold text-white rounded-full uppercase tracking-wide">{{ $t }}</span>
                    @endforeach
                </div>

                {{-- Stats --}}
                <div class="space-y-2 mb-4">
                    @foreach(['hp' => ['HP', 'fa-heart'], 'attack' => ['Ataque', 'fa-hand-fist'], 'defense' => ['Defensa', 'fa-shield-halved']] as $key => [$label, $icon])
                    <div class="flex items-center gap-3">
                        <div class="w-6 text-center">
                            <i class="fa-solid {{ $icon }} text-gray-500 text-xs"></i>
                        </div>
                        <span class="text-xs text-gray-400 w-16">{{ $label }}</span>
                        <div class="flex-1 h-2 bg-gray-700 rounded-full overflow-hidden">
                            <div class="h-full rounded-full {{ $isWinnerB ? 'bg-green-500' : 'bg-gray-500' }}"
                                 style="width: {{ min(100, round($pokemonB[$key] / 255 * 100)) }}%"></div>
                        </div>
                        <span class="text-xs font-bold text-white w-8 text-right">{{ $pokemonB[$key] }}</span>
                    </div>
                    @endforeach
                </div>

                {{-- Score --}}
                <div class="border-t border-gray-700 pt-3 text-center">
                    <p class="text-xs text-gray-500 uppercase tracking-widest mb-1">Score Total</p>
                    <p class="text-3xl font-black {{ $isWinnerB ? 'text-green-400' : ($isDrawA ? 'text-yellow-500' : 'text-gray-400') }}">
                        {{ $scoreB }}
                    </p>
                    <p class="text-xs text-gray-600 mt-1">{{ $pokemonB['hp'] }} + {{ $pokemonB['attack'] }} + {{ $pokemonB['defense'] }}</p>
                </div>
            </div>
        </div>

    </div>{{-- /grid --}}

    {{-- FÓRMULA --}}
    <div class="mt-6 p-4 bg-gray-800/50 border border-gray-700 rounded-xl text-center max-w-xl mx-auto">
        <p class="text-xs text-gray-500 uppercase tracking-widest mb-1">Fórmula de comparación</p>
        <code class="text-sm text-yellow-500 font-bold">score = hp + attack + defense</code>
        <p class="text-xs text-gray-600 mt-1">
            BattleService::score() · BattleService::winner()
        </p>
    </div>

    {{-- OTRA BATALLA --}}
    <div class="text-center mt-6">
        <a href="{{ route('battle') }}"
           class="inline-block px-8 py-3 font-black text-gray-900 uppercase bg-yellow-500 rounded-xl hover:bg-yellow-400 transition-all text-sm shadow-lg hover:-translate-y-1">
            <i class="fa-solid fa-rotate mr-2"></i> Nueva comparación
        </a>
    </div>

    @endif

</div>
@endsection
