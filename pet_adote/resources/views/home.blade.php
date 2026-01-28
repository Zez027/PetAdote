@extends('layouts.app')

@section('content')
<div class="container py-4">
    
    {{-- Cabeçalho da Página --}}
    <div class="text-center mb-5">
        <h1 class="fw-bold text-primary">Encontre seu novo melhor amigo</h1>
        <p class="text-muted text-uppercase fw-semibold" style="letter-spacing: 2px;">Milhares de animais esperando por um lar</p>
    </div>

    {{-- BARRA DE FILTROS INTELIGENTE (SEM BOTÃO BUSCAR) --}}
    <div class="card shadow-sm border-0 rounded-4 mb-5">
        <div class="card-body p-4 bg-white">
            <form action="{{ route('home') }}" method="GET" id="filter-form" class="row g-3 align-items-end">
                
                {{-- Busca por Nome - Espaçamento Ampliado --}}
                <div class="col-md-4">
                    <label class="form-label small fw-bold text-secondary">Nome do Pet</label>
                    <input type="text" name="search" id="search-input" class="form-control bg-light border-0 py-2" 
                           placeholder="Digite para buscar..." value="{{ request('search') }}" autocomplete="off">
                </div>

                {{-- Espécie --}}
                <div class="col-md-2">
                    <label class="form-label small fw-bold text-secondary">Espécie</label>
                    <select name="tipo" class="form-select bg-light border-0 py-2 filter-select">
                        <option value="">Todos</option>
                        <option value="cao" {{ request('tipo') == 'cao' ? 'selected' : '' }}>Cães</option>
                        <option value="gato" {{ request('tipo') == 'gato' ? 'selected' : '' }}>Gatos</option>
                    </select>
                </div>

                {{-- Porte --}}
                <div class="col-md-2">
                    <label class="form-label small fw-bold text-secondary">Porte</label>
                    <select name="porte" class="form-select bg-light border-0 py-2 filter-select">
                        <option value="">Todos</option>
                        <option value="pequeno" {{ request('porte') == 'pequeno' ? 'selected' : '' }}>Pequeno</option>
                        <option value="medio" {{ request('porte') == 'medio' ? 'selected' : '' }}>Médio</option>
                        <option value="grande" {{ request('porte') == 'grande' ? 'selected' : '' }}>Grande</option>
                    </select>
                </div>

                {{-- Cidade - Espaçamento Ampliado --}}
                <div class="col-md-4">
                    <label class="form-label small fw-bold text-secondary">Cidade</label>
                    <select name="cidade" class="form-select bg-light border-0 py-2 filter-select">
                        <option value="">Todas as cidades</option>
                        @foreach($cidades as $cidade)
                            <option value="{{ $cidade }}" {{ request('cidade') == $cidade ? 'selected' : '' }}>
                                {{ $cidade }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Link para limpar filtros --}}
                @if(request()->anyFilled(['search', 'tipo', 'porte', 'cidade']))
                    <div class="col-12 text-end mt-2">
                        <a href="{{ route('home') }}" class="text-decoration-none small text-danger fw-bold">
                            <i class="bi bi-trash3"></i> Limpar filtros ativos
                        </a>
                    </div>
                @endif
            </form>
        </div>
    </div>

    {{-- VITRINE DE PETS --}}
    <div class="row" id="pets-container">
        @forelse($pets as $pet)
            <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
                <div class="card h-100 border-0 shadow-sm hover-card bg-white" style="border-radius: 20px; overflow: hidden;">
                    
                    {{-- Foto do Pet --}}
                    <div class="position-relative overflow-hidden" style="height: 220px;">
                        @php
                            $fotoPrincipal = $pet->photos->where('is_main', true)->first() ?? $pet->photos->first();
                            $urlFoto = $fotoPrincipal ? asset('storage/' . $fotoPrincipal->foto) : asset('images/sem-foto.png');
                        @endphp
                        
                        <img src="{{ $urlFoto }}" class="w-100 h-100 pet-card-img" style="object-fit: cover;" alt="{{ $pet->nome }}">
                        
                        {{-- Badge de Gênero --}}
                        <div class="position-absolute bottom-0 start-0 m-2">
                            <span class="badge {{ $pet->genero == 'macho' ? 'bg-primary' : 'bg-danger' }} rounded-pill px-3 shadow-sm">
                                <i class="bi {{ $pet->genero == 'macho' ? 'bi-gender-male' : 'bi-gender-female' }}"></i>
                                {{ ucfirst($pet->genero) }}
                            </span>
                        </div>
                    </div>

                    <div class="card-body p-3 d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <h5 class="card-title fw-bold text-dark mb-0 text-truncate">{{ $pet->nome }}</h5>
                            <span class="badge bg-light text-secondary border small">{{ ucfirst($pet->porte) }}</span>
                        </div>

                        <p class="card-text text-muted small mb-3">
                            <i class="bi bi-geo-alt-fill text-danger"></i> {{ $pet->cidade }} - {{ $pet->estado }}
                        </p>

                        <div class="mt-auto">
                            <a href="{{ route('pets.show', $pet->id) }}" class="btn btn-outline-primary w-100 rounded-pill fw-bold btn-sm">
                                Ver Detalhes
                            </a>
                        </div>
                    </div>

                    {{-- Footer: Doador --}}
                    <div class="card-footer bg-light border-0 py-2">
                        <div class="d-flex align-items-center">
                            @if($pet->user->profile_photo)
                                <img src="{{ asset('storage/' . $pet->user->profile_photo) }}" 
                                     class="rounded-circle me-2" width="28" height="28" style="object-fit: cover;">
                            @else
                                <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center me-2" style="width: 28px; height: 28px; font-size: 0.75rem;">
                                    {{ substr($pet->user->name, 0, 1) }}
                                </div>
                            @endif
                            <small class="text-muted">Por: <strong>{{ explode(' ', $pet->user->name)[0] }}</strong></small>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <i class="bi bi-search text-muted" style="font-size: 3rem;"></i>
                <h4 class="mt-3 text-muted">Nenhum pet encontrado com esses filtros.</h4>
                <a href="{{ route('home') }}" class="btn btn-primary rounded-pill mt-2">Ver todos os Pets</a>
            </div>
        @endforelse
    </div>
</div>

<style>
    .hover-card { transition: all 0.3s ease; }
    .hover-card:hover { transform: translateY(-8px); box-shadow: 0 12px 24px rgba(0,0,0,0.12) !important; }
    .pet-card-img { transition: transform 0.5s ease; }
    .hover-card:hover .pet-card-img { transform: scale(1.08); }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('search-input');
        const filterForm = document.getElementById('filter-form');
        const selects = document.querySelectorAll('.filter-select');
        let typingTimer;

        // Submeter formulário ao digitar
        searchInput.addEventListener('input', function () {
            clearTimeout(typingTimer);
            typingTimer = setTimeout(function() {
                filterForm.submit();
            }, 600); 
        });

        // Submeter ao alterar selects
        selects.forEach(select => {
            select.addEventListener('change', function() {
                filterForm.submit();
            });
        });

        // Manter o foco e cursor no final do input após o refresh
        if (searchInput.value !== "") {
            const length = searchInput.value.length;
            searchInput.focus();
            searchInput.setSelectionRange(length, length);
        }
    });
</script>
@endsection