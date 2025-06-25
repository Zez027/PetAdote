@extends('layouts.app')

@section('content')
<h1>Login</h1>

@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<form method="POST" action="{{ route('login') }}">
    @csrf
    <div class="mb-3">
        <label>Email</label>
        <input type="email" name="email" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Senha</label>
        <input type="password" name="password" class="form-control" required>
    </div>

    <button class="btn btn-primary">Entrar</button>
</form>

<p class="mt-3">
    <a href="{{ route('password.request') }}">Esqueci minha senha</a>
</p>
@endsection
