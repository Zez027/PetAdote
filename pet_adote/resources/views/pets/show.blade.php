@extends('layouts.app')

@section('content')
<div class="container py-5">
    {{-- Topo: Voltar e Favoritar --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="{{ route('home') }}" class="btn btn-light rounded-pill shadow-sm px-4">
            <i class="bi bi-arrow-left me-2"></i> Voltar para a busca
        </a>
        
        {{-- Lógica de Favorito --}}
        <form action="{{ route('pets.favorite', $pet->id) }}" method="POST">
            @csrf
            @php
                $jaFavoritou = auth()->check() && auth()->user()->favorites()->where('pet_id', $pet->id)->exists();
            @endphp
            <button type="submit" class="btn {{ $jaFavoritou ? 'btn-danger' : 'btn-outline-danger' }} rounded-pill shadow-sm px-4">
                <i class="bi {{ $jaFavoritou ? 'bi-heart-fill' : 'bi-heart' }} me-2"></i>
                {{ $jaFavoritou ? 'Favoritado' : 'Favoritar' }}
            </button>
        </form>
    </div>

    <div class="row g-4">
        {{-- COLUNA ESQUERDA: GALERIA DE FOTOS --}}
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="position-relative">
                    {{-- Foto Principal --}}
                    @php
                        $fotoPrincipal = $pet->photos->where('is_main', true)->first() ?? $pet->photos->first();
                        $urlPrincipal = $fotoPrincipal ? asset('storage/' . $fotoPrincipal->foto) : asset('images/sem-foto.png');
                    @endphp
                    <img id="main-photo" src="{{ $urlPrincipal }}" 
                         class="img-fluid w-100" style="height: 500px; object-fit: cover;">
                    
                    <span class="position-absolute top-0 start-0 m-3 badge bg-{{ $pet->status == 'disponivel' ? 'success' : 'warning' }} px-3 py-2 shadow">
                        {{ ucfirst($pet->status) }}
                    </span>
                </div>
                
                {{-- Miniaturas --}}
                @if($pet->photos->count() > 1)
                <div class="d-flex p-3 gap-2 bg-light overflow-auto">
                    @foreach($pet->photos as $photo)
                        <img src="{{ asset('storage/' . $photo->foto) }}" 
                             class="rounded-3 thumb-img border {{ $photo->is_main ? 'border-primary border-2' : '' }}" 
                             width="80" height="80" style="object-fit: cover; cursor: pointer;"
                             onclick="changePhoto(this.src, this)">
                    @endforeach
                </div>
                @endif
            </div>
        </div>

        {{-- COLUNA DIREITA: INFORMAÇÕES E ADOÇÃO --}}
        <div class="col-lg-5">
            <div class="mb-4">
                <h1 class="fw-bold text-dark mb-1">{{ $pet->nome }}</h1>
                <p class="text-muted mb-3"><i class="bi bi-geo-alt-fill text-danger"></i> {{ $pet->cidade }} - {{ $pet->estado }}</p>
                
                {{-- Grid de Características --}}
                <div class="row g-2 mb-3">
                    <div class="col-4">
                        <div class="bg-white p-3 rounded-4 border text-center shadow-sm">
                            <i class="bi bi-gender-{{ $pet->genero == 'macho' ? 'male' : 'female' }} text-{{ $pet->genero == 'macho' ? 'primary' : 'danger' }} fs-4"></i>
                            <small class="text-muted d-block mt-1">Gênero</small>
                            <span class="fw-bold small">{{ ucfirst($pet->genero) }}</span>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="bg-white p-3 rounded-4 border text-center shadow-sm">
                            <i class="bi bi-rulers text-warning fs-4"></i>
                            <small class="text-muted d-block mt-1">Porte</small>
                            <span class="fw-bold small">{{ ucfirst($pet->porte) }}</span>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="bg-white p-3 rounded-4 border text-center shadow-sm">
                            <i class="bi bi-info-circle text-info fs-4"></i>
                            <small class="text-muted d-block mt-1">Raça</small>
                            <span class="fw-bold small text-truncate d-block">{{ $pet->raca }}</span>
                        </div>
                    </div>
                </div>

                {{-- Badges de Saúde --}}
                <div class="d-flex flex-wrap gap-2 mb-4">
                    @if($pet->vacinado) <span class="badge bg-success-subtle text-success border border-success px-3 rounded-pill"><i class="bi bi-shield-check"></i> Vacinado</span> @endif
                    @if($pet->castrado) <span class="badge bg-primary-subtle text-primary border border-primary px-3 rounded-pill"><i class="bi bi-scissors"></i> Castrado</span> @endif
                    @if($pet->vermifugado) <span class="badge bg-info-subtle text-info border border-info px-3 rounded-pill"><i class="bi bi-capsule"></i> Vermifugado</span> @endif
                </div>
            </div>

            <div class="mb-4">
                <h5 class="fw-bold">Descrição</h5>
                <p class="text-secondary" style="line-height: 1.6;">{{ $pet->descricao }}</p>
            </div>

            <hr class="my-4 opacity-25">

            {{-- CARD DO DOADOR E SOLICITAÇÃO --}}
            <div class="card border-0 bg-light rounded-4 p-4">
                <div class="d-flex align-items-center mb-4">
                    @if($pet->user->profile_photo)
                        <img src="{{ asset('storage/' . $pet->user->profile_photo) }}" class="rounded-circle border border-2 border-white shadow-sm me-3" width="60" height="60" style="object-fit: cover;">
                    @else
                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-3 shadow-sm" style="width: 60px; height: 60px; font-size: 1.5rem;">
                            {{ substr($pet->user->name, 0, 1) }}
                        </div>
                    @endif
                    <div>
                        <h6 class="mb-0 fw-bold">{{ $pet->user->name }}</h6>
                        <small class="text-muted">Doador(a) responsável</small>
                    </div>
                </div>

                @php
                    $pedidoExistente = \App\Models\AdoptionRequest::where('user_id', auth()->id())
                                        ->where('pet_id', $pet->id)
                                        ->first();
                @endphp

                @if(auth()->id() != $pet->user_id)
                    @if(!$pedidoExistente)
                        <form action="{{ route('adocoes.store', $pet->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary btn-lg w-100 rounded-pill fw-bold shadow mb-3">
                                <i class="bi bi-chat-heart me-2"></i> Tenho Interesse na Adoção
                            </button>
                        </form>
                    @else
                        <div class="alert alert-warning border-0 rounded-4 text-center mb-3">
                            <i class="bi bi-clock-history me-2"></i> 
                            Pedido <strong>{{ ucfirst($pedidoExistente->status) }}</strong>
                        </div>
                        @if($pedidoExistente->status == 'aprovado')
                            <a href="https://wa.me/55{{ preg_replace('/\D/', '', $pet->user->contato) }}" target="_blank" class="btn btn-success btn-lg w-100 rounded-pill fw-bold mb-3">
                                <i class="bi bi-whatsapp me-2"></i> Entrar em Contato
                            </a>
                        @endif
                    @endif
                    
                    {{-- Redes Sociais --}}
                    <div class="d-flex justify-content-center gap-3">
                        @if($pet->user->instagram)
                            <a href="{{ $pet->user->instagram }}" target="_blank" class="text-danger fs-5"><i class="bi bi-instagram"></i></a>
                        @endif
                        @if($pet->user->facebook)
                            <a href="{{ $pet->user->facebook }}" target="_blank" class="text-primary fs-5"><i class="bi bi-facebook"></i></a>
                        @endif
                    </div>
                @else
                    <div class="alert alert-info border-0 rounded-4 text-center small mb-0">
                        <i class="bi bi-info-circle me-2"></i> Você gerencia este anúncio.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
    function changePhoto(src, thumb) {
        document.getElementById('main-photo').src = src;
        document.querySelectorAll('.thumb-img').forEach(img => img.classList.remove('border-primary', 'border-2'));
        thumb.classList.add('border-primary', 'border-2');
    }
</script>
@endsection