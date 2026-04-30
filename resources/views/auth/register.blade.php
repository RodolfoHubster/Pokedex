@extends('layouts.app')

@section('content')
<div class="flex items-center justify-center min-h-[75vh]">
    <div class="w-full max-w-md p-8 shadow-2xl bg-gray-800 rounded-xl border-l-4 border-yellow-500">
        <h2 class="mb-8 text-3xl font-bold tracking-widest text-center text-yellow-500 uppercase">Crear Cuenta</h2>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="mb-6">
                <label for="name" class="block mb-2 text-xs font-bold tracking-wide text-gray-400 uppercase">Nombre</label>
                <input id="name" class="block w-full px-4 py-3 text-white placeholder-gray-500 bg-gray-900 border border-gray-700 rounded-md focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="Tu nombre" />
                <x-input-error :messages="$errors->get('name')" class="mt-2 text-red-400" />
            </div>

            <div class="mb-6">
                <label for="email" class="block mb-2 text-xs font-bold tracking-wide text-gray-400 uppercase">Email</label>
                <input id="email" class="block w-full px-4 py-3 text-white placeholder-gray-500 bg-gray-900 border border-gray-700 rounded-md focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="tu@correo.com" />
                <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-400" />
            </div>

            <div class="mb-6">
                <label for="password" class="block mb-2 text-xs font-bold tracking-wide text-gray-400 uppercase">Contraseña</label>
                <input id="password" class="block w-full px-4 py-3 text-white placeholder-gray-500 bg-gray-900 border border-gray-700 rounded-md focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent" type="password" name="password" required autocomplete="new-password" placeholder="••••••••" />
                <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-400" />
            </div>

            <div class="mb-8">
                <label for="password_confirmation" class="block mb-2 text-xs font-bold tracking-wide text-gray-400 uppercase">Confirmar Contraseña</label>
                <input id="password_confirmation" class="block w-full px-4 py-3 text-white placeholder-gray-500 bg-gray-900 border border-gray-700 rounded-md focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="••••••••" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-red-400" />
            </div>

            <div class="flex items-center justify-between mt-8">
                <a class="text-sm font-medium text-yellow-500 transition hover:text-yellow-400" href="{{ route('login') }}">
                    ¿Ya tienes cuenta?
                </a>

                <button type="submit" class="px-6 py-3 font-bold tracking-widest text-gray-900 uppercase transition bg-yellow-500 rounded-md hover:bg-yellow-400 focus:outline-none focus:ring-4 focus:ring-yellow-500/50">
                    Registrarme
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
