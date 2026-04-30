@extends('layouts.app')

@section('content')

{{-- Hero --}}
<div class="relative flex flex-col items-center justify-center py-20 text-center overflow-hidden">

    {{-- Pokeball decorativa de fondo --}}
    <div class="absolute opacity-5 pointer-events-none" style="width:600px;height:600px;top:50%;left:50%;transform:translate(-50%,-50%)">
        <svg viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
            <circle cx="50" cy="50" r="48" fill="#EAB308" stroke="#EAB308" stroke-width="1"/>
            <rect x="2" y="46" width="96" height="8" fill="#EAB308"/>
            <circle cx="50" cy="50" r="14" fill="#EAB308"/>
        </svg>
    </div>

    <div class="relative z-10">
        <p class="text-xs font-bold tracking-widest text-yellow-500 uppercase mb-4">Bienvenido a</p>
        <h1 class="text-6xl font-black tracking-widest text-white uppercase mb-2">
            Poké<span class="text-yellow-500">dex</span>
        </h1>
        <p class="mt-4 text-gray-400 text-lg max-w-md mx-auto">
            Explora los <span class="text-yellow-500 font-bold">+1000 Pokémon</span> de todas las generaciones. Guarda tus favoritos y construye tu equipo ideal.
        </p>

        <div class="flex flex-col sm:flex-row gap-4 justify-center mt-10">
            <a href="/pokemon" class="px-8 py-4 text-sm font-black tracking-widest text-gray-900 uppercase bg-yellow-500 rounded-xl hover:bg-yellow-400 transition-all duration-200 shadow-lg shadow-yellow-500/30 hover:-translate-y-1">
                🔍 Explorar Pokémon
            </a>
            @guest
            <a href="{{ route('register') }}" class="px-8 py-4 text-sm font-bold tracking-widest text-gray-300 uppercase border border-gray-600 rounded-xl hover:border-yellow-500 hover:text-yellow-500 transition-all duration-200">
                Crear cuenta gratis
            </a>
            @endguest
        </div>
    </div>
</div>

{{-- Mini grid de tipos --}}
<div class="max-w-2xl mx-auto mt-8 mb-16">
    <p class="text-center text-xs text-gray-500 tracking-widest uppercase mb-5">Tipos disponibles</p>
    <div class="flex flex-wrap gap-2 justify-center">
        @foreach(['fire','water','grass','electric','ice','fighting','poison','psychic','bug','dragon','ghost','dark','steel','fairy','normal','ground','flying','rock'] as $type)
        <a href="/pokemon?search={{ $type }}"
           class="type-{{ $type }} px-3 py-1 text-xs font-bold text-white rounded-full uppercase tracking-wide hover:opacity-80 transition-opacity">
            {{ $type }}
        </a>
        @endforeach
    </div>
</div>

@endsection