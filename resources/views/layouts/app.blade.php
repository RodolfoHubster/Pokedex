<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Pokédex Laravel</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased text-gray-100 bg-gray-900">
    
    <nav class="bg-gray-900 border-b border-gray-800 shadow-lg">
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="/" class="text-2xl font-bold tracking-widest text-yellow-500 uppercase">
                        Pokédex
                    </a>
                    <div class="hidden ml-10 md:flex space-x-8">
                        <a href="/" class="px-3 py-2 text-sm font-medium tracking-wide text-gray-300 uppercase transition rounded-md hover:text-yellow-500">Inicio</a>
                        <a href="/pokemon" class="px-3 py-2 text-sm font-medium tracking-wide text-gray-300 uppercase transition rounded-md hover:text-yellow-500">Pokémon</a>
                        <a href="/about" class="px-3 py-2 text-sm font-medium tracking-wide text-gray-300 uppercase transition rounded-md hover:text-yellow-500">Acerca de</a>
                        @auth
                            <a href="{{ route('favorites.index') }}" class="px-3 py-2 text-sm font-bold tracking-wide text-gray-900 uppercase transition bg-yellow-500 rounded-md hover:bg-yellow-400">⭐ Mis Pokémon</a>
                        @endauth
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    @guest
                        <a href="{{ route('login') }}" class="text-sm font-medium tracking-wide text-gray-300 uppercase transition hover:text-white">Iniciar sesión</a>
                        <a href="{{ route('register') }}" class="px-4 py-2 text-sm font-bold tracking-wide text-gray-900 uppercase transition bg-yellow-500 rounded-md hover:bg-yellow-400">Registrarse</a>
                    @else
                        <span class="text-sm font-bold tracking-wide text-yellow-500 uppercase">{{ auth()->user()->name }}</span>
                        <form method="POST" action="{{ route('logout') }}" class="inline m-0">
                            @csrf
                            <button type="submit" class="text-sm font-medium tracking-wide text-gray-500 uppercase transition hover:text-red-400">Cerrar sesión</button>
                        </form>
                    @endguest
                </div>
            </div>
        </div>
    </nav>

    <main class="container px-4 py-8 mx-auto">
        @yield('content')
    </main>

</body>
</html>