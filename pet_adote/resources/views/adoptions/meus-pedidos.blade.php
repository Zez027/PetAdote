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
                    <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden">
                        {{-- Foto do Pet --}}
                        <div class="position-relative">
                            @php
                                $fotoPrincipal = $pedido->pet->photos->where('is_main', true)->first() ?? $pedido->pet->photos->first();
                                $urlPrincipal = $fotoPrincipal ? asset('storage/' . $fotoPrincipal->foto) : asset('images/sem-foto.png');
                            @endphp
                            <img src="{{ $urlPrincipal }}" class="card-img-top object-fit-cover" style="height: 200px;" alt="{{ $pedido->pet->nome }}">
                            
                            {{-- Badge de Status --}}
                            <div class="position-absolute top-0 end-0 p-3">
                                @if($pedido->status === 'pendente')
                                    <span class="badge bg-warning text-dark fs-6 shadow"><i class="bi bi-hourglass-split"></i> Pendente</span>
                                @elseif($pedido->status === 'em_analise')
                                    <span class="badge bg-info text-dark fs-6 shadow"><i class="bi bi-search"></i> Em Análise</span>
                                @elseif($pedido->status === 'aprovado')
                                    <span class="badge bg-success fs-6 shadow"><i class="bi bi-check-circle"></i> Aprovado</span>
                                @elseif($pedido->status === 'rejeitado')
                                    <span class="badge bg-danger fs-6 shadow"><i class="bi bi-x-circle"></i> Rejeitado</span>
                                @endif
                            </div>
                        </div>

                        <div class="card-body p-4">
                            <h4 class="card-title fw-bold mb-3">{{ $pedido->pet->nome }}</h4>
                            
                            <ul class="list-unstyled mb-4 text-muted">
                                <li class="mb-2"><i class="bi bi-calendar-event me-2"></i> Pedido em: {{ $pedido->created_at->format('d/m/Y') }}</li>
                                <li><i class="bi bi-person-heart me-2"></i> Doador: {{ $pedido->pet->user->name }}</li>
                            </ul>

                            @if($pedido->status === 'rejeitado' && $pedido->motivo_rejeicao)
                                <div class="alert alert-danger p-2 fs-7 rounded-3">
                                    <strong>Motivo:</strong> {{ $pedido->motivo_rejeicao }}
                                </div>
                            @endif

                            @if($pedido->status === 'em_analise')
                                <div class="alert alert-info p-2 fs-7 rounded-3">
                                    O doador está avaliando sua solicitação e pode entrar em contato com você em breve.
                                </div>
                            @endif
                        </div>

                        <div class="card-footer bg-white border-top-0 p-4 pt-0 d-grid gap-2">
                            <a href="{{ route('pets.show', $pedido->pet->id) }}" class="btn btn-outline-primary rounded-pill fw-bold">
                                Ver Detalhes do Pet
                            </a>
                            <button class="btn btn-light rounded-pill fw-bold" data-bs-toggle="modal" data-bs-target="#historicoModal{{ $pedido->id }}">
                                <i class="bi bi-clock-history"></i> Ver Histórico
                            </button>

                            @if($pedido->status === 'aprovado')
                                <a href="{{ route('adoptions.contract', $pedido->id) }}" class="btn btn-sm btn-outline-dark fw-bold rounded-pill">
                                <i class="bi bi-file-earmark-pdf text-danger"></i> Termo de Adoção
                                </a>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Modal de Histórico --}}
                <div class="modal fade" id="historicoModal{{ $pedido->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                        <div class="modal-content border-0 rounded-4">
                            <div class="modal-header bg-light border-0 p-4">
                                <h5 class="modal-title fw-bold"><i class="bi bi-clock-history me-2 text-primary"></i> Histórico da Solicitação</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body p-4">
                                @if(isset($pedido->statusLogs) && $pedido->statusLogs->count() > 0)
                                    <div class="timeline position-relative">
                                        @foreach($pedido->statusLogs as $log)
                                            <div class="d-flex mb-4 position-relative z-1">
                                                <div class="me-3">
                                                    @if($log->status === 'pendente')
                                                        <div class="bg-warning text-dark rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 40px; height: 40px;">
                                                            <i class="bi bi-hourglass-split"></i>
                                                        </div>
                                                    @elseif($log->status === 'em_analise')
                                                        <div class="bg-info text-dark rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 40px; height: 40px;">
                                                            <i class="bi bi-search"></i>
                                                        </div>
                                                    @elseif($log->status === 'aprovado')
                                                        <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 40px; height: 40px;">
                                                            <i class="bi bi-check-lg"></i>
                                                        </div>
                                                    @elseif($log->status === 'rejeitado')
                                                        <div class="bg-danger text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 40px; height: 40px;">
                                                            <i class="bi bi-x-lg"></i>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div>
                                                    <h6 class="fw-bold mb-1">
                                                        Status alterado para 
                                                        <span class="text-capitalize">{{ str_replace('_', ' ', $log->status) }}</span>
                                                    </h6>
                                                    <p class="text-muted mb-1 fs-7">
                                                        <i class="bi bi-calendar3 me-1"></i> {{ $log->created_at->format('d/m/Y H:i') }}
                                                    </p>
                                                    @if($log->observacao)
                                                        <div class="bg-light p-2 rounded fs-7 mt-2 text-muted">
                                                            "{{ $log->observacao }}"
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <style>
                                        .timeline::before {
                                            content: '';
                                            position: absolute;
                                            left: 20px;
                                            top: 0;
                                            bottom: 0;
                                            width: 2px;
                                            background-color: #e9ecef;
                                            z-index: 0;
                                        }
                                    </style>
                                @else
                                    <div class="text-center py-4">
                                        <p class="text-muted">Nenhum histórico detalhado disponível.</p>
                                    </div>
                                @endif
                            </div>
                            <div class="modal-footer border-0 p-4 pt-0">
                                <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Fechar</button>
                            </div>
                        </div>
                    </div>
                </div>

            @endforeach
        </div>

        @if(method_exists($requests, 'hasPages') && $requests->hasPages())
            <div class="mt-5 d-flex justify-content-center">
                {{ $requests->links() }}
            </div>
        @endif
    @endif
</div>
@endsection