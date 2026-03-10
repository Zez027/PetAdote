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
            {{ $totalRequests ?? 0 }} pedidos no total
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

    {{-- Organização das Abas em Array --}}
    @php
        $grupos = [
            ['id' => 'pendentes', 'titulo' => 'Pendentes', 'cor' => 'warning', 'text_color' => 'text-dark', 'lista' => $pendentes, 'icone' => 'bi-hourglass-split'],
            ['id' => 'aprovados', 'titulo' => 'Aprovados', 'cor' => 'success', 'text_color' => '', 'lista' => $aprovados, 'icone' => 'bi-check-circle'],
            ['id' => 'rejeitados', 'titulo' => 'Rejeitados', 'cor' => 'secondary', 'text_color' => '', 'lista' => $rejeitados, 'icone' => 'bi-x-circle'],
        ];
    @endphp

    {{-- Navegação das Abas --}}
    <ul class="nav nav-tabs mb-4 border-bottom-0 gap-2" id="adoptionTabs" role="tablist">
        @foreach($grupos as $index => $grupo)
            <li class="nav-item" role="presentation">
                <button class="nav-link border-0 fw-bold {{ $index === 0 ? 'active border-bottom border-primary border-3 text-primary' : 'text-muted' }}" 
                        id="{{ $grupo['id'] }}-tab" 
                        data-bs-toggle="tab" 
                        data-bs-target="#{{ $grupo['id'] }}" 
                        type="button" role="tab" 
                        style="{{ $index === 0 ? 'background: transparent;' : '' }}">
                    {{ $grupo['titulo'] }} 
                    <span class="badge bg-{{ $grupo['cor'] }} {{ $grupo['text_color'] }} ms-1 rounded-pill">{{ $grupo['lista']->total() }}</span>
                </button>
            </li>
        @endforeach
    </ul>

    {{-- Conteúdo das Abas --}}
    <div class="tab-content" id="adoptionTabsContent">
        @foreach($grupos as $index => $grupo)
            <div class="tab-pane fade {{ $index === 0 ? 'show active' : '' }}" id="{{ $grupo['id'] }}" role="tabpanel" tabindex="0">
                <div class="row g-4">
                    @forelse($grupo['lista'] as $pedido)
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
                                                    <form action="{{ route('adoptions.updateStatus', $pedido->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="hidden" name="status" value="aprovado">
                                                        <button type="submit" class="btn btn-sm btn-success">
                                                            <i class="bi bi-check-lg"></i> Aprovar
                                                        </button>
                                                    </form>
                                                    <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#rejeitarModal{{ $pedido->id }}">
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
                                                <small class="text-muted fw-bold d-block mb-1">NOME</small>
                                                <span class="fs-6 text-dark fw-medium">{{ $pedido->user->name }}</span>
                                            </li>
                                            <li class="list-group-item px-0 border-0 mb-3 bg-transparent">
                                                <small class="text-muted fw-bold d-block mb-1">EMAIL</small>
                                                <span class="fs-6 text-dark fw-medium">{{ $pedido->user->email }}</span>
                                            </li>
                                            <li class="list-group-item px-0 border-0 mb-3 bg-transparent">
                                                <small class="text-muted fw-bold d-block mb-1">TELEFONE</small>
                                                <span class="fs-6 text-dark fw-medium">{{ $pedido->user->contato ?? 'Não informado' }}</span>
                                            </li>
                                            <li class="list-group-item px-0 border-0 mb-3 bg-transparent">
                                                <small class="text-muted fw-bold d-block mb-1">TIPO DE MORADIA</small>
                                                <span class="fs-6 text-dark fw-medium">{{ $pedido->user->tipo_residencia ?? 'Não informado' }}</span>
                                            </li>
                                        </ul>
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
                                                <label for="motivo_rejeicao" class="form-label fw-bold">Motivo da Rejeição</label>
                                                <textarea class="form-control" name="motivo_rejeicao" id="motivo_rejeicao" rows="3" placeholder="Explique o motivo..."></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer border-0 p-4 pt-0">
                                            <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancelar</button>
                                            <button type="submit" class="btn btn-danger rounded-pill px-4">Confirmar</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                    @empty
                        <div class="col-12 text-center py-5 mt-4 border border-dashed rounded-4 bg-light bg-opacity-50">
                            <i class="{{ $grupo['icone'] }} text-muted mb-3 d-block" style="font-size: 3rem;"></i>
                            <h5 class="text-secondary fw-bold">Nenhuma solicitação em "{{ $grupo['titulo'] }}"</h5>
                        </div>
                    @endforelse
                </div>

                {{-- PAGINAÇÃO COM ESTILO DA HOME --}}
                <div class="d-flex justify-content-center mt-5 mb-4">
                    @if($grupo['id'] === 'pendentes')
                        {{ $pendentes->appends(['aprovados_page' => $aprovados->currentPage(), 'rejeitados_page' => $rejeitados->currentPage()])->links() }}
                    @elseif($grupo['id'] === 'aprovados')
                        {{ $aprovados->appends(['pendentes_page' => $pendentes->currentPage(), 'rejeitados_page' => $rejeitados->currentPage()])->links() }}
                    @elseif($grupo['id'] === 'rejeitados')
                        {{ $rejeitados->appends(['pendentes_page' => $pendentes->currentPage(), 'aprovados_page' => $aprovados->currentPage()])->links() }}
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>

<style>
     /* Customização da Paginação PetAdote */
    .pagination {
        gap: 5px; /* Espaço entre os números */
    }

    .pagination .page-item .page-link {
        border-radius: 10px !important; /* Botões arredondados */
        border: none;
        color: #0d6efd; /* Cor azul do seu projeto */
        padding: 8px 16px;
        background-color: #f8f9fa;
        transition: all 0.3s ease;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }

    .pagination .page-item.active .page-link {
        background-color: #0d6efd !important; /* Azul escuro para página ativa */
        color: white !important;
        font-weight: bold;
        box-shadow: 0 4px 8px rgba(13, 110, 253, 0.3);
    }

    .pagination .page-item .page-link:hover {
        background-color: #e9ecef;
        transform: translateY(-2px); /* Efeito de levante */
        color: #0a58ca;
    }

    /* Esconder o texto "Showing X to Y of Z" para ficar mais minimalista */
    .pagination-wrapper nav > div:first-child {
        display: none !important;
    }
</style>
@endsection