@extends('layouts.app')

@section('content')
<h1>Recuperar Senha</h1>

@if (session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
@endif

<form method="POST" action="{{ route('password.email') }}">
    @csrf
    <div class="mb-3">
        <label>Email cadastrado *</label>
        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" required>
        @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <button class="btn btn-primary">Enviar link de recuperação</button>
</form>
@endsection
