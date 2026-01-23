@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-4">

        <div class="card shadow-lg border-0 mt-4">
            <div class="card-header text-center bg-primary text-white">
                <h4>Recuperar Senha</h4>
            </div>

            <div class="card-body">

                @if (session('status'))
                    <div class="alert alert-success text-center">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">E-mail</label>
                        <input 
                            id="email" 
                            type="email" 
                            class="form-control @error('email') is-invalid @enderror"
                            name="email" 
                            value="{{ old('email') }}" 
                            required 
                            autocomplete="email" 
                            autofocus>

                        @error('email')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary w-100 mt-2">
                        Enviar Link de Recuperação
                    </button>

                    <div class="text-center mt-3">
                        <a href="{{ route('login') }}">Voltar para Login</a>
                    </div>
                </form>

            </div>
        </div>

    </div>
</div>
@endsection
