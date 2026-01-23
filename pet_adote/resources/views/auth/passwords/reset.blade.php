@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">

        <div class="card card-custom">
            <div class="card-header-custom text-center">
                <strong>Redefinir Senha</strong>
            </div>

            <div class="card-body">

                <form method="POST" action="{{ route('password.update') }}">
                    @csrf

                    <input type="hidden" name="token" value="{{ $token }}">

                    <div class="mb-3">
                        <label>Email cadastrado *</label>
                        <input 
                            type="email" 
                            name="email" 
                            class="form-control @error('email') is-invalid @enderror" 
                            value="{{ old('email') }}" 
                            required
                        >
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label>Nova senha *</label>
                        <input 
                            type="password" 
                            name="password" 
                            class="form-control @error('password') is-invalid @enderror" 
                            required
                        >
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label>Confirmar nova senha *</label>
                        <input 
                            type="password" 
                            name="password_confirmation" 
                            class="form-control" 
                            required
                        >
                    </div>

                    <button class="btn btn-green w-100">Atualizar senha</button>
                </form>

            </div>
        </div>

    </div>
</div>
@endsection
