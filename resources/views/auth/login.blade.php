@extends('layouts.app')

@section('content')
<div class="flex items-center justify-center min-h-[75vh]">
    <div class="w-full max-w-md p-8 shadow-2xl bg-gray-800 rounded-xl border-l-4 border-yellow-500">
        <h2 class="mb-8 text-3xl font-bold tracking-widest text-center text-yellow-500 uppercase">Iniciar Sesión</h2>

        <x-auth-session-status class="mb-4 text-yellow-500" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-6">
                <label for="email" class="block mb-2 text-xs font-bold tracking-wide text-gray-400 uppercase">Email</label>
                <input id="email" class="block w-full px-4 py-3 text-white placeholder-gray-500 bg-gray-900 border border-gray-700 rounded-md focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="tu@correo.com" />
                <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-400" />
            </div>

            <div class="mb-6">
                <label for="password" class="block mb-2 text-xs font-bold tracking-wide text-gray-400 uppercase">Contraseña</label>
                <input id="password" class="block w-full px-4 py-3 text-white placeholder-gray-500 bg-gray-900 border border-gray-700 rounded-md focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent" type="password" name="password" required autocomplete="current-password" placeholder="••••••••" />
                <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-400" />
            </div>

            <div class="flex items-center justify-between mb-8">
                <label for="remember_me" class="inline-flex items-center">
                    <input id="remember_me" type="checkbox" class="w-4 h-4 text-yellow-500 bg-gray-900 border-gray-700 rounded focus:ring-yellow-500 focus:ring-offset-gray-800" name="remember">
                    <span class="ml-2 text-sm font-medium text-gray-400">Recordarme</span>
                </label>

                @if (Route::has('password.request'))
                    <a class="text-sm font-medium text-yellow-500 transition hover:text-yellow-400" href="{{ route('password.request') }}">
                        ¿Olvidaste tu contraseña?
                    </a>
                @endif
            </div>

            <button type="submit" class="w-full px-4 py-3 font-bold tracking-widest text-gray-900 uppercase transition bg-yellow-500 rounded-md hover:bg-yellow-400 focus:outline-none focus:ring-4 focus:ring-yellow-500/50">
                Entrar
            </button>
        </form>
    </div>
</div>
@endsection