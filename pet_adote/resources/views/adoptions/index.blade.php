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
            {{ $requests->total() ?? $requests->count() }} pedidos no total
        </span>
    </div>

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
                                $fotoPrincipal = $pedido->pet->photos->where('is_main', true)->first() ?? $pedido->pet->photos->first();
                                $urlPrincipal = $fotoPrincipal ? asset('storage/' . $fotoPrincipal->foto) : asset('images/sem-foto.png');
                            @endphp
                            <img src="{{ $urlPrincipal }}" class="img-fluid w-100 object-fit-cover" style="height: 220px;" alt="Foto do Pet">
                        </div>

                        {{-- Coluna de Informações e Botões --}}
                        <div class="col-md-9 col-lg-10 p-4">
                            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start mb-2">
                                <div class="mb-3 mb-md-0">
                                    <h5 class="fw-bold mb-1">
                                        {{ $pedido->pet->nome }}
                                        
                                        {{-- Badges de Status --}}
                                        @if($pedido->status === 'pendente')
                                            <span class="badge bg-warning text-dark ms-2 align-middle"><i class="bi bi-hourglass-split"></i> Pendente</span>
                                        @elseif($pedido->status === 'em_analise')
                                            <span class="badge bg-info text-dark ms-2 align-middle"><i class="bi bi-search"></i> Em Análise / Entrevista</span>
                                        @elseif($pedido->status === 'aprovado')
                                            <span class="badge bg-success ms-2 align-middle"><i class="bi bi-check-circle"></i> Aprovado</span>
                                        @elseif($pedido->status === 'rejeitado')
                                            <span class="badge bg-danger ms-2 align-middle"><i class="bi bi-x-circle"></i> Rejeitado</span>
                                        @endif
                                    </h5>
                                    <p class="text-muted mb-0"><i class="bi bi-person me-1"></i> Interessado(a): <strong>{{ $pedido->user->name }}</strong></p>
                                    <small class="text-muted"><i class="bi bi-calendar-event me-1"></i> Pedido enviado em {{ $pedido->created_at->format('d/m/Y H:i') }}</small>
                                </div>
                                
                                {{-- Ações --}}
                                <div class="d-flex flex-wrap gap-2">
                                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#perfilModal{{ $pedido->id }}">
                                        <i class="bi bi-person-vcard"></i> Ver Perfil
                                    </button>

                                    @if($pedido->status === 'aprovado')
                                        <a href="{{ route('adoptions.contract', $pedido->id) }}" class="btn btn-sm btn-outline-dark fw-bold rounded-pill">
                                            <i class="bi bi-file-earmark-pdf text-danger"></i> Termo de Adoção
                                        </a>
                                    @endif

                                    @if($pedido->status === 'pendente')
                                        {{-- Botão Iniciar Entrevista --}}
                                        <form action="{{ route('adoptions.updateStatus', $pedido->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="status" value="em_analise">
                                            <button type="submit" class="btn btn-sm btn-outline-info">
                                                <i class="bi bi-chat-dots"></i> Iniciar Entrevista
                                            </button>
                                        </form>
                                    @endif

                                    @if(in_array($pedido->status, ['pendente', 'em_analise']))
                                        {{-- Botão Aprovar --}}
                                        <form action="{{ route('adoptions.updateStatus', $pedido->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="status" value="aprovado">
                                            <button type="submit" class="btn btn-sm btn-success">
                                                <i class="bi bi-check-lg"></i> Aprovar
                                            </button>
                                        </form>

                                        {{-- Botão Rejeitar --}}
                                        <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#rejeitarModal{{ $pedido->id }}">
                                            <i class="bi bi-x-lg"></i> Rejeitar
                                        </button>
                                    @endif
                                </div>
                            </div>
                            
                            @if($pedido->status === 'rejeitado' && $pedido->motivo_rejeicao)
                                <div class="alert alert-danger mt-3 mb-0 p-2 fs-7 rounded-3">
                                    <strong>Motivo da rejeição:</strong> {{ $pedido->motivo_rejeicao }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Modal Ver Perfil --}}
                <div class="modal fade" id="perfilModal{{ $pedido->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content border-0 rounded-4">
                            <div class="modal-header bg-primary bg-opacity-10 rounded-top-4 p-4">
                                <h5 class="modal-title fw-bold text-primary"><i class="bi bi-person-vcard me-2"></i> Ficha do Adotante</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body p-4">
                                <ul class="list-group list-group-flush">

                                        <li class="list-group-item px-0 border-0 mb-3 bg-transparent">
                                            <small class="text-muted fw-bold d-block mb-1"><i class="bi bi-person-fill me-2 text-secondary"></i>NOME</small>
                                            <span class="fs-6 text-dark fw-medium">{{ $pedido->user->name }}</span>
                                        </li>

                                        <li class="list-group-item px-0 border-0 mb-3 bg-transparent">
                                            <small class="text-muted fw-bold d-block mb-1"><i class="bi bi-envelope-at-fill me-2 text-secondary"></i>EMAIL</small>
                                            <span class="fs-6 text-dark fw-medium">{{ $pedido->user->email }}</span>
                                        </li>

                                        <li class="list-group-item px-0 border-0 mb-3 bg-transparent">
                                            <small class="text-muted fw-bold d-block mb-1"><i class="bi bi-whatsapp me-2 text-secondary"></i>TELEFONE</small>
                                            <span class="fs-6 text-dark fw-medium">{{ $pedido->user->contato ?? 'Não informado' }}</span>
                                        </li>
                                        <li class="list-group-item px-0 border-0 mb-3 bg-transparent">
                                            <small class="text-muted fw-bold d-block mb-1"><i class="bi bi-house-door-fill me-2 text-secondary"></i>TIPO DE MORADIA</small>
                                            <span class="fs-6 text-dark fw-medium">{{ $pedido->user->tipo_residencia ?? 'Não informado' }}</span>
                                        </li>
                                        <li class="list-group-item px-0 border-0 mb-3 bg-transparent">
                                            <small class="text-muted fw-bold d-block mb-1"><i class="bi bi-shield-shaded me-2 text-secondary"></i>SEGURANÇA (TELAS/MUROS)</small>
                                            <span class="fs-6 text-dark fw-medium">{{ $pedido->user->seguranca ?? 'Não informado' }}</span>
                                        </li>
                                        <li class="list-group-item px-0 border-0 mb-3 bg-transparent">
                                            <small class="text-muted fw-bold d-block mb-1"><i class="bi bi-info-square-fill me-2 text-secondary"></i>OUTROS ANIMAIS NA CASA?</small>
                                            <span class="fs-6 text-dark fw-medium">{{ $pedido->user->outros_pets ?? 'Não informado' }}</span>
                                        </li>
                                        <li class="list-group-item px-0 border-0 mb-3 bg-transparent">
                                            <small class="text-muted fw-bold d-block mb-1"><i class="bi bi-people-fill me-2 text-secondary"></i>CRIANÇAS EM CASA?</small>
                                            <span class="fs-6 text-dark fw-medium">{{ $pedido->user->criancas ?? 'Não informado' }}</span>
                                        </li>
                                        <li class="list-group-item px-0 border-0 bg-transparent border-top pt-3">
                                            <small class="text-muted fw-bold d-block mb-1"><i class="bi bi-clock-fill me-2 text-secondary"></i>TEMPO SOZINHO POR DIA</small>
                                            <span class="fs-6 text-dark fw-medium">{{ $pedido->user->tempo_sozinho ?? 'Não informado' }}</span>
                                        </li>
                                    </ul>
                                @if($pedido->user->adopter_profile)
                                    <hr>
                                    <h6><strong>Perfil e Experiência:</strong></h6>
                                    <p class="text-muted">{{ $pedido->user->adopter_profile }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Modal Rejeitar --}}
                <div class="modal fade" id="rejeitarModal{{ $pedido->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content border-0 rounded-4">
                            <form action="{{ route('adoptions.updateStatus', $pedido->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="rejeitado">
                                <div class="modal-header bg-danger bg-opacity-10 rounded-top-4 p-4">
                                    <h5 class="modal-title fw-bold text-danger"><i class="bi bi-x-circle me-2"></i> Rejeitar Pedido</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body p-4">
                                    <div class="mb-3">
                                        <label for="motivo_rejeicao" class="form-label fw-bold">Motivo da Rejeição (Opcional)</label>
                                        <textarea class="form-control" name="motivo_rejeicao" id="motivo_rejeicao" rows="3" placeholder="Explique o motivo de forma empática para o adotante..."></textarea>
                                        <small class="text-muted">Isso ajudará o adotante a entender a decisão.</small>
                                    </div>
                                </div>
                                <div class="modal-footer border-0 p-4 pt-0">
                                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancelar</button>
                                    <button type="submit" class="btn btn-danger rounded-pill px-4">Confirmar Rejeição</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        @empty
            <div class="col-12 text-center py-5">
                <i class="bi bi-inbox text-muted mb-3" style="font-size: 3rem;"></i>
                <h4 class="text-secondary fw-bold">Nenhuma solicitação recebida ainda</h4>
                <p class="text-muted">Quando alguém quiser adotar seus pets, os pedidos aparecerão aqui.</p>
            </div>
        @endforelse
    </div>
    
    @if(method_exists($requests, 'hasPages') && $requests->hasPages())
        <div class="mt-4 d-flex justify-content-center">
            {{ $requests->links() }}
        </div>
    @endif
</div>
@endsection