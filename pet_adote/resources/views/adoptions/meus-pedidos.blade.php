@extends('layouts.app')

@section('content')
<div class="container py-5">
    
    {{-- Cabeçalho da Página --}}
    <div class="mb-5">
        <h2 class="fw-bold text-dark mb-1">
            <i class="bi bi-clipboard2-heart text-primary me-2"></i> Meus Pedidos
        </h2>
        <p class="text-muted">Acompanhe aqui o status das suas solicitações de adoção.</p>
    </div>

    @if($requests->isEmpty())
        <div class="card border-0 shadow-sm rounded-4 p-5 text-center">
            <div class="mb-3">
                <i class="bi bi-send-exclamation text-muted" style="font-size: 3.5rem;"></i>
            </div>
            <h4 class="text-secondary">Nenhum pedido realizado.</h4>
            <p class="text-muted">Explore os pets disponíveis e envie uma solicitação para começar!</p>
            <div class="mt-3">
                <a href="{{ route('home') }}" class="btn btn-primary rounded-pill px-4 fw-bold">Explorar Pets</a>
            </div>
        </div>
    @else
        <div class="row g-4">
            @foreach($requests as $pedido)
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden hover-up">
                        
                        {{-- Topo do Card: Foto do Pet --}}
                        <div class="position-relative" style="height: 180px;">
                             @php
                                $foto = $pedido->pet->photos->where('is_main', true)->first() ?? $pedido->pet->photos->first();
                                $urlFoto = $foto ? asset('storage/' . $foto->foto) : asset('images/sem-foto.png');
                            @endphp
                            <img src="{{ $urlFoto }}" class="w-100 h-100" style="object-fit: cover;" alt="{{ $pedido->pet->name }}">
                            
                            {{-- Badge de Status Flutuante --}}
                            <div class="position-absolute top-0 end-0 m-3">
                                @if($pedido->status == 'pendente')
                                    <span class="badge bg-warning text-dark rounded-pill px-3 shadow-sm">
                                        <i class="bi bi-clock-history me-1"></i> Pendente
                                    </span>
                                @elseif($pedido->status == 'aprovado')
                                    <span class="badge bg-success rounded-pill px-3 shadow-sm">
                                        <i class="bi bi-check-circle-fill me-1"></i> Aprovado
                                    </span>
                                @else
                                    <span class="badge bg-danger rounded-pill px-3 shadow-sm">
                                        <i class="bi bi-x-circle-fill me-1"></i> Rejeitado
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="card-body p-4">
                            <h5 class="fw-bold text-dark mb-1">{{ $pedido->pet->name }}</h5>
                            <p class="text-muted small mb-3">
                                <i class="bi bi-geo-alt me-1"></i> {{ $pedido->pet->city ?? $pedido->pet->cidade }} - {{ $pedido->pet->state ?? $pedido->pet->estado }}
                            </p>

                            <div class="d-flex align-items-center mb-4 p-2 bg-light rounded-3">
                                @if($pedido->pet->user->profile_photo)
                                    <img src="{{ asset('storage/' . $pedido->pet->user->profile_photo) }}" 
                                         class="rounded-circle me-2" width="30" height="30" style="object-fit: cover;">
                                @else
                                    <i class="bi bi-person-circle me-2 text-secondary"></i>
                                @endif
                                <small class="text-muted">Doador: <strong>{{ explode(' ', $pedido->pet->user->name)[0] }}</strong></small>
                            </div>

                            <div class="d-grid gap-2">
                                <a href="{{ route('pets.show', $pedido->pet_id) }}" class="btn btn-outline-primary rounded-pill fw-bold">
                                    Ver Detalhes
                                </a>

                                @if($pedido->status == 'aprovado')
                                    @php
                                        // Limpa o número para o link do WhatsApp
                                        $contato = $pedido->pet->user->phone ?? $pedido->pet->user->contato;
                                        $whatsapp = preg_replace('/\D/', '', $contato);
                                    @endphp
                                    <a href="https://wa.me/55{{ $whatsapp }}" 
                                       target="_blank" class="btn btn-success rounded-pill fw-bold shadow-sm">
                                        <i class="bi bi-whatsapp me-2"></i> Chamar no Whats
                                    </a>
                                @endif
                            </div>
                        </div>

                        <div class="card-footer bg-white border-0 text-center pb-3">
                            <small class="text-muted" style="font-size: 0.7rem;">
                                Pedido enviado em {{ $pedido->created_at->format('d/m/Y') }}
                            </small>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Adicionado paginação que faltava no seu original --}}
        <div class="mt-4 d-flex justify-content-center">
            {{ $requests->links() }}
        </div>
    @endif
</div>

<style>
    .hover-up {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .hover-up:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.1) !important;
    }
</style>
@endsection