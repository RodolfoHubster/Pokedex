<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Pokédex</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        .pokeball-bg {
            background-image: radial-gradient(circle, rgba(255,255,255,0.03) 1px, transparent 1px);
            background-size: 32px 32px;
        }
        .type-fire    { background-color: #F08030; }
        .type-water   { background-color: #6890F0; }
        .type-grass   { background-color: #78C850; }
        .type-electric{ background-color: #F8D030; color:#333; }
        .type-ice     { background-color: #98D8D8; color:#333; }
        .type-fighting{ background-color: #C03028; }
        .type-poison  { background-color: #A040A0; }
        .type-ground  { background-color: #E0C068; color:#333; }
        .type-flying  { background-color: #A890F0; }
        .type-psychic { background-color: #F85888; }
        .type-bug     { background-color: #A8B820; }
        .type-rock    { background-color: #B8A038; }
        .type-ghost   { background-color: #705898; }
        .type-dragon  { background-color: #7038F8; }
        .type-dark    { background-color: #705848; }
        .type-steel   { background-color: #B8B8D0; color:#333; }
        .type-fairy   { background-color: #EE99AC; color:#333; }
        .type-normal  { background-color: #A8A878; color:#333; }
    </style>
</head>
<body class="font-sans antialiased text-gray-100 bg-gray-900 pokeball-bg">

    <nav class="bg-gray-900/95 border-b border-yellow-500/30 shadow-lg shadow-yellow-500/5 sticky top-0 z-50 backdrop-blur-sm">
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">

                {{-- Logo --}}
                <div class="flex items-center gap-8">
                    <a href="/" class="flex items-center gap-3 group">
                        {{-- Pokeball SVG --}}
                        <svg class="w-8 h-8 transition-transform duration-300 group-hover:rotate-180" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="50" cy="50" r="48" fill="#EF4444" stroke="#1f2937" stroke-width="4"/>
                            <path d="M2 50 Q2 2 50 2 Q98 2 98 50" fill="#EF4444"/>
                            <path d="M2 50 Q2 98 50 98 Q98 98 98 50" fill="#f9fafb"/>
                            <rect x="2" y="46" width="96" height="8" fill="#1f2937"/>
                            <circle cx="50" cy="50" r="14" fill="#1f2937"/>
                            <circle cx="50" cy="50" r="9" fill="#f9fafb"/>
                            <circle cx="50" cy="50" r="5" fill="#EF4444"/>
                        </svg>
                        <span class="text-xl font-black tracking-widest text-yellow-500 uppercase">Pokédex</span>
                    </a>

                    <div class="hidden md:flex space-x-1">
                        <a href="/" class="px-3 py-2 text-sm font-medium tracking-wide text-gray-300 uppercase transition rounded-md hover:text-yellow-500 hover:bg-gray-800">Inicio</a>
                        <a href="/pokemon" class="px-3 py-2 text-sm font-medium tracking-wide text-gray-300 uppercase transition rounded-md hover:text-yellow-500 hover:bg-gray-800">Pokémon</a>
                        <a href="/about" class="px-3 py-2 text-sm font-medium tracking-wide text-gray-300 uppercase transition rounded-md hover:text-yellow-500 hover:bg-gray-800">Acerca de</a>
                        <a href="{{ route('battle') }}" class="px-3 py-2 text-sm font-medium tracking-wide text-gray-300 uppercase transition rounded-md hover:text-yellow-500 hover:bg-gray-800"><i class="fa-solid fa-bolt mr-1"></i> Battle</a>
                        @auth
                            <a href="{{ route('favorites.index') }}" class="px-3 py-2 text-sm font-bold tracking-wide text-gray-900 uppercase transition bg-yellow-500 rounded-md hover:bg-yellow-400"><i class="fa-solid fa-star mr-1"></i> Mis Pokémon</a>
                        @endauth
                    </div>
                </div>

                {{-- Auth --}}
                <div class="flex items-center gap-3">
                    @guest
                        <a href="{{ route('login') }}" class="text-sm font-medium tracking-wide text-gray-300 uppercase transition hover:text-white">Ingresar</a>
                        <a href="{{ route('register') }}" class="px-4 py-2 text-sm font-bold tracking-wide text-gray-900 uppercase transition bg-yellow-500 rounded-md hover:bg-yellow-400">Registro</a>
                    @else
                        <a href="{{ route('dashboard') }}" class="flex items-center gap-2 text-sm font-bold tracking-wide text-yellow-500 uppercase hover:text-yellow-300 transition-colors duration-150">
                            <div class="w-7 h-7 bg-yellow-500 rounded-full flex items-center justify-center text-gray-900 font-black text-xs">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                            {{ auth()->user()->name }}
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="inline m-0">
                            @csrf
                            <button type="submit" class="text-xs font-medium tracking-wide text-gray-500 uppercase transition hover:text-red-400">Salir</button>
                        </form>
                    @endguest
                </div>

            </div>
        </div>
    </nav>

    <main class="container px-4 py-8 mx-auto">
        @yield('content')
    </main>

    <footer class="mt-16 border-t border-gray-800 py-6 text-center text-xs text-gray-600 tracking-widest uppercase">
        Pokédex · Laravel 12 · PokeAPI
    </footer>

</body>
</html>