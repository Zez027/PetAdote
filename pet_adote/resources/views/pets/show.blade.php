@extends('layouts.app')

@section('content')
<div class="container py-4">

    {{-- TÍTULO --}}
    <h2 class="mb-4 text-center fw-bold text-success">
        {{ $pet->nome }}
    </h2>

    <div class="row g-4">

        {{-- FOTOS EM CARROSSEL --}}
        <div class="col-md-6">
            <div id="petCarousel" class="carousel slide shadow-sm rounded" data-bs-ride="carousel">

                <div class="carousel-inner">

                    @forelse ($pet->photos as $index => $photo)
                        <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                            {{-- AQUI: Usando a classe CSS padronizada em vez de style inline --}}
                            <img 
                                src="{{ asset('storage/' . $photo->foto) }}" 
                                class="d-block w-100 rounded img-padrao-detalhe"
                                alt="Foto de {{ $pet->nome }}"
                            >
                        </div>
                    @empty
                        {{-- Caso não tenha fotos --}}
                        <div class="carousel-item active">
                            <img 
                                src="{{ asset('images/sem-foto.png') }}" 
                                class="d-block w-100 rounded img-padrao-detalhe"
                                alt="Sem foto disponível"
                            >
                        </div>
                    @endforelse

                </div>

                {{-- Controles (só aparecem se tiver mais de 1 foto) --}}
                @if($pet->photos->count() > 1)
                    <button class="carousel-control-prev" type="button" data-bs-target="#petCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Anterior</span>
                    </button>

                    <button class="carousel-control-next" type="button" data-bs-target="#petCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Próximo</span>
                    </button>
                @endif

            </div>
        </div>

        {{-- INFORMAÇÕES DO PET --}}
        <div class="col-md-6">
            <div class="card shadow-sm border-0 rounded-3 h-100">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Informações do Pet</h5>
                </div>
                <div class="card-body">

                    <p><strong>Idade:</strong> {{ $pet->idade }} anos</p>
                    <p><strong>Gênero:</strong> {{ $pet->genero }}</p>
                    <p><strong>Raça:</strong> {{ $pet->raca }}</p>
                    <p><strong>Porte:</strong> {{ ucfirst($pet->porte) }}</p>
                    <p><strong>Tipo:</strong> {{ ucfirst($pet->tipo) }}</p>

                    {{-- ÍCONES DE SAÚDE --}}
                    <div class="mb-3 mt-3">
                        @if($pet->vacinado) <span class="badge bg-success"><i class="bi bi-shield-check"></i> Vacinado</span> @endif
                        @if($pet->castrado) <span class="badge bg-primary"><i class="bi bi-scissors"></i> Castrado</span> @endif
                        @if($pet->vermifugado) <span class="badge bg-info text-dark"><i class="bi bi-capsule"></i> Vermifugado</span> @endif
                    </div>

                    <p class="mt-3"><strong>Localização:</strong><br>
                        📍 {{ $pet->cidade }}, {{ $pet->estado }}
                    </p>

                    <p class="mt-3"><strong>Descrição:</strong><br>
                        {{ $pet->descricao }}
                    </p>

                    {{-- BOTÕES DE AÇÃO --}}
                    <div class="d-flex gap-2 mt-4 align-items-center">
                        
                        {{-- Lógica do Botão de Favoritar --}}
                        <form action="{{ route('pets.favorite', $pet->id) }}" method="POST">
                            @csrf
                            
                            @php
                                // Verifica se o usuário está logado e se já favoritou
                                $jaFavoritou = auth()->check() && auth()->user()->favorites()->where('pet_id', $pet->id)->exists();
                            @endphp

                            @if($jaFavoritou)
                                {{-- Botão VERMELHO SÓLIDO (Para desfavoritar) --}}
                                <button type="submit" class="btn btn-danger" title="Remover dos favoritos">
                                    ❤️ Favoritado
                                </button>
                            @else
                                {{-- Botão TRANSPARENTE (Para favoritar) --}}
                                <button type="submit" class="btn btn-outline-danger" title="Adicionar aos favoritos">
                                    🤍 Favoritar
                                </button>
                            @endif
                        </form>

                        {{-- Botão de Adotar --}}
                        <form action="{{ route('pets.adopt', $pet->id) }}" method="POST" class="flex-grow-1">
                            @csrf
                            <button type="submit" class="btn btn-success btn-lg w-100 shadow-sm">
                                🐾 Quero Adotar
                            </button>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- INFORMAÇÕES DO DOADOR --}}
    <h3 class="mt-5 mb-3 text-success fw-bold">Contato do Doador</h3>

    <div class="card shadow-sm border-0 rounded-3 mb-5">
        <div class="card-body">
            <div class="d-flex align-items-center mb-4">
                {{-- Foto do Doador --}}
                @if($pet->user->profile_photo)
                    <img src="{{ asset('storage/' . $pet->user->profile_photo) }}" 
                        class="rounded-circle me-3 shadow-sm border" 
                        width="80" height="80" style="object-fit: cover;">
                @else
                    <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center me-3 shadow-sm" 
                        style="width: 80px; height: 80px; font-size: 2rem;">
                        {{ substr($pet->user->name, 0, 1) }}
                    </div>
                @endif

                <div>
                    <h4 class="mb-0 fw-bold">{{ $pet->user->name }}</h4>
                    <p class="text-muted mb-0">
                        <i class="bi bi-geo-alt-fill text-danger"></i> 
                        {{ $pet->user->cidade }} - {{ $pet->user->estado }}
                    </p>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    @if($pet->user->email)
                        <p><strong>Email:</strong><br>
                            <a href="mailto:{{ $pet->user->email }}" class="text-decoration-none text-success fw-bold">
                                {{ $pet->user->email }}
                            </a>
                        </p>
                    @endif
                </div>
                
                <div class="col-md-6">
                    @if($pet->user->contato)
                        <p><strong>Whatsapp:</strong><br>
                            <a href="https://wa.me/55{{ preg_replace('/\D/', '', $pet->user->contato) }}"
                            target="_blank" class="text-decoration-none text-success fw-bold">
                                <i class="bi bi-whatsapp"></i> {{ $pet->user->contato }}
                            </a>
                        </p>
                    @endif
                </div>
            </div>

            <div class="mt-3 border-top pt-3">
                @if($pet->user->instagram)
                    <a href="{{ $pet->user->instagram }}" target="_blank" class="btn btn-sm btn-outline-secondary me-2">
                    <i class="bi bi-instagram"></i> Instagram
                    </a>
                @endif

                @if($pet->user->facebook)
                    <a href="{{ $pet->user->facebook }}" target="_blank" class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-facebook"></i> Facebook
                    </a>
                @endif
            </div>

        </div>
    </div>

</div>
@endsection