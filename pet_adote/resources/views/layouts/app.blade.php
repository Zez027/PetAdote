<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Pet Adoção</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
    <nav class="mb-4">
        <a href="{{ route('home') }}">Início</a>
        <a href="{{ route('perfil.edit') }}">Meu Perfil</a>
        | <a href="{{ route('pets.meus') }}">Meus Pets</a>
        @auth
            | <a href="{{ route('pets.create') }}">Novo Pet</a>
            | <form method="POST" action="{{ route('logout') }}" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-sm btn-danger">Logout</button>
            </form>
        @else
            | <a href="{{ route('login') }}">Login</a>
            | <a href="{{ route('register') }}">Cadastro</a>
        @endauth
    </nav>

    <div class="container">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @yield('content')
    </div>
</body>
</html>
