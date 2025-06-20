@extends('layouts.app')

@section('content')
<h1>Login</h1>
<form method="POST" action="{{ route('login') }}">
    @csrf
    <div class="mb-3"><label>Email</label><input type="email" name="email" class="form-control" required></div>
    <div class="mb-3"><label>Senha</label><input type="password" name="password" class="form-control" required></div>
    <button class="btn btn-primary">Entrar</button>
</form>
@endsection
