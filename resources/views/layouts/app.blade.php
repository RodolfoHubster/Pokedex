<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Pokédex Laravel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f0f4f8; }
        .navbar { background-color: #cc0000 !important; }
        .navbar-brand, .nav-link { color: white !important; font-weight: bold; }
        .nav-link:hover { color: #ffcb05 !important; }
        .navbar-toggler { border-color: rgba(255,255,255,0.5); }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="/">Pokédex</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
                <span class="navbar-toggler-icon" style="filter:invert(1)"></span>
            </button>
            <div class="collapse navbar-collapse" id="navMenu">
                <div class="navbar-nav me-auto">
                    <a class="nav-link" href="/">Inicio</a>
                    <a class="nav-link" href="/pokemon">Pokémon</a>
                    <a class="nav-link" href="/about">Acerca de</a>
                    @auth
                        <a class="nav-link" href="{{ route('favorites.index') }}">⭐ Mis Pokémon</a>
                    @endauth
                </div>
                <div class="navbar-nav ms-auto">
                    @guest
                        <a class="nav-link" href="{{ route('login') }}">Iniciar sesión</a>
                        <a class="nav-link" href="{{ route('register') }}">Registrarse</a>
                    @else
                        <span class="nav-link" style="color:#ffcb05">{{ auth()->user()->name }}</span>
                        <form method="POST" action="{{ route('logout') }}" class="d-inline">
                            @csrf
                            <button type="submit" class="nav-link btn btn-link p-0" style="color:white;text-decoration:none">Cerrar sesión</button>
                        </form>
                    @endguest
                </div>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
