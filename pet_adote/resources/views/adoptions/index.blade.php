@extends('layouts.app')

@section('content')
<div class="container py-5">
    
    {{-- Header da Página --}}
    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
        <div>
            <h2 class="fw-bold text-dark mb-0"><i class="bi bi-inbox-fill text-primary me-2"></i> Solicitações Recebidas</h2>
            <p class="text-muted mb-0 mt-1">Gerencie quem tem interesse em adotar os seus pets.</p>
        </div>
        <span class="badge bg-primary rounded-pill px-3 py-2 shadow-sm fs-6">
            {{ $requests->total() }} pedidos no total
        </span>
    </div>

    {{-- Alertas Corrigidos (Sem o erro de View Not Found) --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 rounded-4 shadow-sm mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show border-0 rounded-4 shadow-sm mb-4" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Lista de Solicitações --}}
    <div class="row g-4">
        @forelse($requests as $pedido)
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="row g-0 align-items-center">
                        
                        {{-- Coluna da Foto do Pet --}}
                        <div class="col-md-3 col-lg-2 bg-light text-center h-100">
                            @php
                                // Pega a foto principal do pet ou a primeira foto salva
                                $fotoPrincipal = $pedido->pet->photos->where('is_main', true)->first() ?? $pedido->pet->photos->first();
                                $urlPrincipal = $fotoPrincipal ? asset('storage/' . $fotoPrincipal->foto) : asset('images/sem-foto.png');
                            @endphp
                            <img src="{{ $urlPrincipal }}" class="img-fluid w-100 object-fit-cover" style="height: 220px;" alt="Foto do Pet">
                        </div>

                        {{-- Coluna de Informações e Botões --}}
                        <div class="col-md-9 col-lg-10">
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div>
                                        <h5 class="fw-bold mb-1">
                                            Pet: <a href="{{ route('pets.show', $pedido->pet->id) }}" class="text-decoration-none text-primary">{{ $pedido->pet->nome }}</a>
                                        </h5>
                                        <p class="text-muted mb-0 small">
                                            <i class="bi bi-person-fill text-secondary"></i> Solicitante: <strong>{{ $pedido->user->name }}</strong> 
                                            <span class="ms-3"><i class="bi bi-calendar-event text-secondary"></i> Data do pedido: {{ $pedido->created_at->format('d/m/Y \à\s H:i') }}</span>
                                        </p>
                                    </div>
                                    
                                    {{-- Status Badge com Cores --}}
                                    <div>
                                        @if($pedido->status == 'pendente')
                                            <span class="badge bg-warning text-dark px-3 py-2 rounded-pill shadow-sm"><i class="bi bi-hourglass-split"></i> Pendente</span>
                                        @elseif($pedido->status == 'aprovado')
                                            <span class="badge bg-success px-3 py-2 rounded-pill shadow-sm"><i class="bi bi-check-circle"></i> Aprovado</span>
                                        @else
                                            <span class="badge bg-danger px-3 py-2 rounded-pill shadow-sm"><i class="bi bi-x-circle"></i> Rejeitado</span>
                                        @endif
                                    </div>
                                </div>

                                {{-- Botões de Ação (Apenas se o pedido ainda for "pendente") --}}
                                @if($pedido->status == 'pendente')
                                    <div class="d-flex gap-3 justify-content-end border-top pt-3">
                                        {{-- Botão Rejeitar --}}
                                       <button type="button" class="btn btn-outline-danger btn-lg rounded-pill px-4 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $pedido->id }}">
                                            <i class="bi bi-x-lg me-1"></i> Rejeitar Adoção
                                        </button>

                                        {{-- Modal de Rejeição --}}
                                        <div class="modal fade" id="rejectModal{{ $pedido->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content border-0 shadow rounded-4">
                                                    <div class="modal-header border-bottom-0 pb-0">
                                                        <h5 class="modal-title fw-bold text-danger"><i class="bi bi-x-circle-fill me-2"></i> Motivo da Rejeição</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <form action="{{ route('adoptions.reject', $pedido->id) }}" method="POST">
                                                        @csrf
                                                        <div class="modal-body text-start">
                                                            <p class="text-muted small mb-3">Informe ao interessado (<strong>{{ $pedido->user->name }}</strong>) o motivo de não prosseguir com a adoção neste momento.</p>
                                                            <textarea name="motivo_rejeicao" class="form-control rounded-3 bg-light border-0 p-3" rows="4" placeholder="Ex: O pet já foi adotado por outra pessoa, ou o perfil não se adequa às necessidades do animal..." required></textarea>
                                                        </div>
                                                        <div class="modal-footer border-top-0 pt-0">
                                                            <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancelar</button>
                                                            <button type="submit" class="btn btn-danger rounded-pill px-4 fw-bold shadow-sm">Confirmar Rejeição</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Botão Aprovar --}}
                                        <form action="{{ route('adoptions.approve', $pedido->id) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja APROVAR esta solicitação? O pet será marcado como adotado e outros pedidos ficarão indisponíveis.');">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-lg rounded-pill px-4 fw-bold shadow-sm">
                                                <i class="bi bi-check-lg me-1"></i> Aprovar Adoção
                                            </button>
                                        </form>
                                    </div>
                                @elseif($pedido->status == 'aprovado')
                                    {{-- Se o pedido já estiver aprovado, exibe botão para chamar no WhatsApp --}}
                                    <div class="d-flex justify-content-end border-top pt-3">
                                        <a href="https://wa.me/55{{ preg_replace('/\D/', '', $pedido->user->contato ?? '') }}" target="_blank" class="btn btn-success btn-lg rounded-pill px-4 fw-bold shadow-sm">
                                            <i class="bi bi-whatsapp me-2"></i> Chamar Adotante no WhatsApp
                                        </a>
                                    </div>
                                @endif

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3 shadow-sm" style="width: 100px; height: 100px;">
                    <i class="bi bi-inbox text-muted" style="font-size: 3rem;"></i>
                </div>
                <h4 class="fw-bold text-secondary">Nenhuma solicitação encontrada</h4>
                <p class="text-muted">Você ainda não recebeu pedidos de adoção para seus pets.</p>
                <a href="{{ route('home') }}" class="btn btn-primary rounded-pill px-4 mt-2 shadow-sm">Explorar Pets</a>
            </div>
        @endforelse
    </div>

    {{-- Paginação --}}
    <div class="d-flex justify-content-center mt-5">
        {{ $requests->links('pagination::bootstrap-5') }}
    </div>

</div>
@endsection