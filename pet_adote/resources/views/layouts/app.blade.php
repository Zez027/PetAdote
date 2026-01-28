<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PetAdopt - Encontre seu amigo</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8f9fa; }
        .navbar { background-color: #fff; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
        .nav-link { font-weight: 600; color: #555; }
        .nav-link:hover { color: #0d6efd; }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg sticky-top mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold text-primary fs-3" href="{{ route('home') }}">
                <i class="bi bi-paw-fill"></i> PetAdopt
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">Explorar</a>
                    </li>

                    @auth
                        {{-- SISTEMA DE NOTIFICAÇÕES --}}
                        @php
                            $notificacoesDoador = \App\Models\AdoptionRequest::whereHas('pet', function($q) {
                                $q->where('user_id', auth()->id());
                            })->where('status', 'pendente')->count();

                            $notificacoesAdotante = \App\Models\AdoptionRequest::where('user_id', auth()->id())
                                ->whereIn('status', ['aprovado', 'rejeitado'])
                                ->where('updated_at', '>=', now()->subDays(3))
                                ->count();
                                
                            $totalNotif = $notificacoesDoador + $notificacoesAdotante;
                        @endphp

                        <li class="nav-item px-2">
                            <a class="nav-link position-relative" href="{{ route('adoptions.index') }}" title="Solicitações">
                                <i class="bi bi-bell fs-5"></i>
                                @if($totalNotif > 0)
                                    <span class="position-absolute top-1 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.65rem;">
                                        {{ $totalNotif }}
                                    </span>
                                @endif
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('pets.meus') }}">Meus Pets</a>
                        </li>
                        
                        <li class="nav-item dropdown ms-lg-3">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDrop" data-bs-toggle="dropdown">
                                @if(auth()->user()->profile_photo)
                                    <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}" class="rounded-circle me-2" width="32" height="32" style="object-fit: cover;">
                                @else
                                    <i class="bi bi-person-circle fs-4 me-2"></i>
                                @endif
                                {{ explode(' ', auth()->user()->name)[0] }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end border-0 shadow-sm">
                                <li><a class="dropdown-item" href="{{ route('perfil.edit') }}"><i class="bi bi-person me-2"></i>Editar Perfil</a></li>
                                <li><a class="dropdown-item" href="{{ route('pets.favoritos') }}"><i class="bi bi-heart me-2"></i>Meus Favoritos</a></li>
                                <li><a class="dropdown-item" href="{{ route('adoptions.meus_pedidos') }}"><i class="bi bi-clipboard2-heart me-2"></i>Meus Pedidos</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger"><i class="bi bi-box-arrow-right me-2"></i>Sair</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item ms-lg-3">
                            <a class="btn btn-outline-primary rounded-pill px-4" href="{{ route('login') }}">Entrar</a>
                        </li>
                        <li class="nav-item ms-lg-2">
                            <a class="btn btn-primary rounded-pill px-4" href="{{ route('register') }}">Cadastrar</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <main>
        @if(session('success'))
            <div class="container mt-3">
                <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>