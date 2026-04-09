@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card shadow">
            <div class="card-body p-4">
                <h3 class="fw-bold mb-4 text-center" style="color:#cc0000">Crear cuenta</h3>

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label fw-bold">Nombre</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name') }}" required autofocus>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Correo electrónico</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email') }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Contraseña</label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Confirmar contraseña</label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-danger">Registrarse</button>
                    </div>
                </form>

                <hr>
                <p class="text-center mb-0">
                    ¿Ya tienes cuenta?
                    <a href="{{ route('login') }}" style="color:#cc0000">Inicia sesión</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
