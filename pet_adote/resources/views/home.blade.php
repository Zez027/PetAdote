@extends('layouts.app')

@section('content')

{{-- BARRA DE FILTROS --}}
<div class="card shadow-sm border-0 mb-5">
    <div class="card-body p-4 bg-light rounded-3">
        <form action="{{ route('home') }}" method="GET">
            <div class="row g-3">
                {{-- Busca por Nome --}}
                <div class="col-md-4">
                    <label class="form-label fw-bold small text-muted">Buscar por nome</label>
                    <input type="text" name="search" class="form-control border-0 shadow-sm" 
                           placeholder="Ex: Rex, Luna..." value="{{ request('search') }}">
                </div>

                {{-- Select Espécie --}}
                <div class="col-md-2">
                    <label class="form-label fw-bold small text-muted">Espécie</label>
                    <select name="tipo" class="form-select border-0 shadow-sm">
                        <option value="">Todos</option>
                        <option value="cao" {{ request('tipo') == 'cao' ? 'selected' : '' }}>Cão</option>
                        <option value="gato" {{ request('tipo') == 'gato' ? 'selected' : '' }}>Gato</option>
                    </select>
                </div>

                {{-- Select Porte --}}
                <div class="col-md-2">
                    <label class="form-label fw-bold small text-muted">Porte</label>
                    <select name="porte" class="form-select border-0 shadow-sm">
                        <option value="">Todos</option>
                        <option value="pequeno" {{ request('porte') == 'pequeno' ? 'selected' : '' }}>Pequeno</option>
                        <option value="medio" {{ request('porte') == 'medio' ? 'selected' : '' }}>Médio</option>
                        <option value="grande" {{ request('porte') == 'grande' ? 'selected' : '' }}>Grande</option>
                    </select>
                </div>

                {{-- Select Cidade (Dinâmico) --}}
                <div class="col-md-2">
                    <label class="form-label fw-bold small text-muted">Cidade</label>
                    <select name="cidade" class="form-select border-0 shadow-sm">
                        <option value="">Todas</option>
                        @foreach($cidades as $cidade)
                            <option value="{{ $cidade }}" {{ request('cidade') == $cidade ? 'selected' : '' }}>
                                {{ $cidade }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Botão Filtrar --}}
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100 fw-bold shadow-sm">
                        <i class="bi bi-search"></i> Buscar
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- LISTA DE PETS --}}
<h3 class="mb-4 fw-bold text-secondary">
    @if(request()->anyFilled(['search', 'tipo', 'porte', 'cidade']))
        <i class="bi bi-funnel"></i> Resultados da busca
        <a href="{{ route('home') }}" class="btn btn-sm btn-link text-decoration-none ms-2">(Limpar Filtros)</a>
    @else
        <i class="bi bi-stars text-warning"></i> Adicionados Recentemente
    @endif
</h3>

<div class="row">
    @forelse($pets as $pet)
        <div class="col-md-3 mb-4">
            <div class="card h-100 shadow-sm border-0 hover-card transition-card">
                {{-- Foto --}}
                <div class="position-relative overflow-hidden rounded-top">
                    @if($pet->photos->first())
                        <img src="{{ asset('storage/' . $pet->photos->first()->foto) }}" 
                             class="card-img-top img-padrao-home" 
                             alt="{{ $pet->nome }}">
                    @else
                        <img src="{{ asset('images/sem-foto.png') }}" 
                             class="card-img-top img-padrao-home" 
                             alt="Sem foto">
                    @endif
                    
                    {{-- Badge Status/Porte --}}
                    <span class="position-absolute top-0 end-0 badge bg-light text-dark m-2 shadow-sm opacity-75">
                        {{ ucfirst($pet->porte) }}
                    </span>
                </div>

                <div class="card-body">
                    <h5 class="card-title fw-bold text-dark mb-1">{{ $pet->nome }}</h5>
                    <p class="card-text text-muted small mb-2">
                        <i class="bi bi-geo-alt-fill text-danger"></i> {{ $pet->cidade }}
                    </p>
                    
                    <div class="d-flex gap-2 mt-3">
                        <a href="{{ route('pets.show', $pet->id) }}" class="btn btn-outline-primary w-100 btn-sm fw-bold">
                            Conhecer
                        </a>
                        
                        {{-- Botão Favoritar Rápido --}}
                        <form action="{{ route('pets.favorite', $pet->id) }}" method="POST">
                            @csrf
                            @php
                                $jaFavoritou = auth()->check() && auth()->user()->favorites()->where('pet_id', $pet->id)->exists();
                            @endphp
                            <button type="submit" class="btn btn-sm {{ $jaFavoritou ? 'btn-danger' : 'btn-outline-danger' }}" 
                                    title="{{ $jaFavoritou ? 'Remover Favorito' : 'Favoritar' }}">
                                <i class="bi {{ $jaFavoritou ? 'bi-heart-fill' : 'bi-heart' }}"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12 text-center py-5">
            <div class="text-muted mb-3">
                <i class="bi bi-search" style="font-size: 3rem;"></i>
            </div>
            <h4>Nenhum pet encontrado com esses filtros.</h4>
            <a href="{{ route('home') }}" class="btn btn-primary mt-3">Ver todos os pets</a>
        </div>
    @endforelse
</div>
@endsection