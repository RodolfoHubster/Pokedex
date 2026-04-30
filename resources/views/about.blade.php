@extends('layouts.app')

@section('content')
<div class="px-4 py-6 mx-auto max-w-4xl">

    {{-- Header --}}
    <div class="mb-12 text-center">
        <p class="text-xs font-bold tracking-widest text-yellow-500 uppercase mb-2">Mini Proyecto · Unidad III</p>
        <h1 class="text-4xl font-black tracking-widest text-white uppercase">Acerca del <span class="text-yellow-500">Proyecto</span></h1>
        <p class="mt-3 text-gray-400">Herramientas de Desarrollo de Software</p>
    </div>

    {{-- Objetivo --}}
    <div class="p-6 mb-6 bg-gray-800 border border-yellow-500/30 rounded-xl shadow-lg shadow-yellow-500/5">
        <div class="flex items-start gap-4">
            <span class="text-3xl">🎯</span>
            <div>
                <h2 class="text-sm font-black tracking-widest text-yellow-500 uppercase mb-2">Objetivo</h2>
                <p class="text-gray-300 leading-relaxed">
                    Construir una <strong class="text-white">Pokédex Web</strong> funcional utilizando el patrón de arquitectura
                    <strong class="text-white">MVC (Modelo–Vista–Controlador)</strong> con Laravel, consumiendo datos en tiempo real
                    desde la <strong class="text-white">PokéAPI</strong>, con sistema de autenticación y almacenamiento local de favoritos.
                </p>
            </div>
        </div>
    </div>

    {{-- Equipo --}}
    <div class="p-6 mb-6 bg-gray-800 border border-gray-700 rounded-xl">
        <h2 class="text-sm font-black tracking-widest text-yellow-500 uppercase mb-5">👥 Equipo</h2>
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div class="flex items-center gap-4 p-4 bg-gray-900 rounded-xl border border-gray-700">
                <div class="w-12 h-12 flex items-center justify-center bg-yellow-500 rounded-full text-gray-900 font-black text-xl flex-shrink-0">R</div>
                <div>
                    <p class="font-black text-white uppercase tracking-wide">Rodolfo Huitron</p>
                    <p class="text-xs text-gray-400 tracking-widest uppercase mt-0.5">Desarrollador</p>
                </div>
            </div>
            <div class="flex items-center gap-4 p-4 bg-gray-900 rounded-xl border border-gray-700">
                <div class="w-12 h-12 flex items-center justify-center bg-red-500 rounded-full text-white font-black text-xl flex-shrink-0">A</div>
                <div>
                    <p class="font-black text-white uppercase tracking-wide">Andrehi Sandoval</p>
                    <p class="text-xs text-gray-400 tracking-widest uppercase mt-0.5">Desarrollador</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Stack tecnológico --}}
    <div class="p-6 mb-6 bg-gray-800 border border-gray-700 rounded-xl">
        <h2 class="text-sm font-black tracking-widest text-yellow-500 uppercase mb-5">🛠️ Stack Tecnológico</h2>
        <div class="grid grid-cols-2 gap-3 sm:grid-cols-3">

            <div class="flex items-center gap-3 p-3 bg-gray-900 rounded-lg border border-gray-700">
                <img src="https://cdn.simpleicons.org/laravel/FF2D20" class="w-7 h-7 flex-shrink-0" alt="Laravel">
                <div>
                    <p class="text-xs font-black text-white uppercase">Laravel 12</p>
                    <p class="text-xs text-gray-500">Framework PHP</p>
                </div>
            </div>

            <div class="flex items-center gap-3 p-3 bg-gray-900 rounded-lg border border-gray-700">
                <img src="https://cdn.simpleicons.org/php/777BB4" class="w-7 h-7 flex-shrink-0" alt="PHP">
                <div>
                    <p class="text-xs font-black text-white uppercase">PHP 8.2</p>
                    <p class="text-xs text-gray-500">Lenguaje base</p>
                </div>
            </div>

            <div class="flex items-center gap-3 p-3 bg-gray-900 rounded-lg border border-gray-700">
                <img src="https://cdn.simpleicons.org/tailwindcss/06B6D4" class="w-7 h-7 flex-shrink-0" alt="Tailwind">
                <div>
                    <p class="text-xs font-black text-white uppercase">Tailwind CSS</p>
                    <p class="text-xs text-gray-500">Estilos UI</p>
                </div>
            </div>

            <div class="flex items-center gap-3 p-3 bg-gray-900 rounded-lg border border-gray-700">
                <img src="https://cdn.simpleicons.org/vite/646CFF" class="w-7 h-7 flex-shrink-0" alt="Vite">
                <div>
                    <p class="text-xs font-black text-white uppercase">Vite</p>
                    <p class="text-xs text-gray-500">Bundler assets</p>
                </div>
            </div>

            <div class="flex items-center gap-3 p-3 bg-gray-900 rounded-lg border border-gray-700">
                <img src="https://cdn.simpleicons.org/sqlite/003B57" class="w-7 h-7 flex-shrink-0" alt="SQLite">
                <div>
                    <p class="text-xs font-black text-white uppercase">SQLite</p>
                    <p class="text-xs text-gray-500">Base de datos</p>
                </div>
            </div>

            <div class="flex items-center gap-3 p-3 bg-gray-900 rounded-lg border border-gray-700">
                <span class="text-2xl flex-shrink-0">🔴</span>
                <div>
                    <p class="text-xs font-black text-white uppercase">PokéAPI</p>
                    <p class="text-xs text-gray-500">API externa REST</p>
                </div>
            </div>

        </div>
    </div>

    {{-- Funcionalidades --}}
    <div class="p-6 mb-6 bg-gray-800 border border-gray-700 rounded-xl">
        <h2 class="text-sm font-black tracking-widest text-yellow-500 uppercase mb-5">⚡ Funcionalidades</h2>
        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
            @foreach([
                ['🔍', 'Explorar Pokémon', 'Listado de +1000 Pokémon con búsqueda por nombre'],
                ['📋', 'Detalle completo', 'Stats, tipos, altura, peso y habilidades de cada Pokémon'],
                ['🔐', 'Autenticación', 'Registro, login y logout con sesiones seguras'],
                ['⭐', 'Sistema de favoritos', 'Guarda tus Pokémon favoritos vinculados a tu cuenta'],
                ['📦', 'Almacenamiento offline', 'Sprites guardados en Base64 en la DB local'],
                ['📊', 'Dashboard personal', 'Estadísticas y accesos rápidos para el usuario'],
            ] as [$icon, $title, $desc])
            <div class="flex items-start gap-3 p-3 bg-gray-900 rounded-lg border border-gray-700">
                <span class="text-xl mt-0.5">{{ $icon }}</span>
                <div>
                    <p class="text-xs font-black text-white uppercase">{{ $title }}</p>
                    <p class="text-xs text-gray-400 mt-0.5">{{ $desc }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Arquitectura MVC --}}
    <div class="p-6 mb-6 bg-gray-800 border border-gray-700 rounded-xl">
        <h2 class="text-sm font-black tracking-widest text-yellow-500 uppercase mb-5">🏗️ Arquitectura MVC</h2>
        <div class="grid grid-cols-3 gap-3 text-center">
            <div class="p-4 bg-red-500/10 border border-red-500/30 rounded-xl">
                <p class="text-2xl mb-2">🗄️</p>
                <p class="text-xs font-black text-red-400 uppercase tracking-widest">Modelo</p>
                <p class="text-xs text-gray-400 mt-2">User · Favorite</p>
                <p class="text-xs text-gray-500 mt-1">Eloquent ORM + SQLite</p>
            </div>
            <div class="p-4 bg-yellow-500/10 border border-yellow-500/30 rounded-xl">
                <p class="text-2xl mb-2">⚙️</p>
                <p class="text-xs font-black text-yellow-400 uppercase tracking-widest">Controlador</p>
                <p class="text-xs text-gray-400 mt-2">PokemonController</p>
                <p class="text-xs text-gray-500 mt-1">FavoriteController</p>
            </div>
            <div class="p-4 bg-green-500/10 border border-green-500/30 rounded-xl">
                <p class="text-2xl mb-2">🖥️</p>
                <p class="text-xs font-black text-green-400 uppercase tracking-widest">Vista</p>
                <p class="text-xs text-gray-400 mt-2">Blade Templates</p>
                <p class="text-xs text-gray-500 mt-1">Tailwind CSS</p>
            </div>
        </div>
    </div>

    {{-- Footer info --}}
    <div class="text-center py-4 text-xs text-gray-600 tracking-widest uppercase">
        Unidad III · Mini Proyecto · UABC · 2026
    </div>

</div>
@endsection