@extends('layouts.app')

@section('content')
<div class="container-fluid mt-4 px-xl-5">
    <div class="row">
        <div class="col-md-2 mb-4">
            <div class="bg-white rounded-3 shadow-sm border-0 h-100 p-3 sticky-top" style="top: 20px; z-index: 100;">
                <h6 class="text-uppercase text-muted fw-bold mb-3 small ms-2 mt-2">Painel Admin</h6>
                <ul class="nav flex-column nav-pills gap-1">
                    <li class="nav-item">
                        <a class="nav-link text-dark" href="{{ route('admin.dashboard') }}">
                            <i class="bi bi-speedometer2 me-2"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-dark" href="{{ route('admin.users.index') }}">
                            <i class="bi bi-people me-2"></i> Usuários
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-dark" href="{{ route('admin.pets.index') }}">
                            <i class="bi bi-suit-heart me-2"></i> Pets
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active bg-primary" href="{{ route('admin.adoptions.index') }}">
                            <i class="bi bi-clipboard2-heart me-2"></i> Adoções
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        
        <div class="col-md-10">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="h3 mb-0 text-dark fw-bold">Gestão de Adoções</h2>
            </div>

            <div class="card shadow-sm border-0 rounded-3 mb-4">
                <div class="card-body p-4">
                    
                    <form action="{{ route('admin.adoptions.index') }}" method="GET" class="mb-4 bg-light p-3 rounded-3 border-0 shadow-sm">
                        <div class="row g-2 align-items-end">
                            <div class="col-md-5">
                                <label class="form-label small text-muted fw-bold mb-1">Buscar</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                                    <input type="text" name="search" class="form-control border-start-0 bg-white" placeholder="Nome do adotante ou pet..." value="{{ request('search') }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small text-muted fw-bold mb-1">Status</label>
                                <select name="status" class="form-select border-0 shadow-sm">
                                    <option value="">Todos os Status</option>
                                    <option value="pendente" {{ request('status') == 'pendente' ? 'selected' : '' }}>Pendente</option>
                                    <option value="aprovado" {{ request('status') == 'aprovado' ? 'selected' : '' }}>Aprovado</option>
                                    <option value="rejeitado" {{ request('status') == 'rejeitado' ? 'selected' : '' }}>Rejeitado</option>
                                    <option value="concluido" {{ request('status') == 'concluido' ? 'selected' : '' }}>Concluído</option>
                                    <option value="cancelado" {{ request('status') == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                                </select>
                            </div>
                            <div class="col-md-3 d-flex gap-2">
                                <button type="submit" class="btn btn-primary w-100 shadow-sm">Filtrar</button>
                                <a href="{{ route('admin.adoptions.index') }}" class="btn btn-outline-secondary bg-white shadow-sm" title="Limpar"><i class="bi bi-eraser"></i></a>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Protocolo</th>
                                    <th>Pet</th>
                                    <th>Adotante</th>
                                    <th>Doador</th>
                                    <th>Status</th>
                                    <th>Data</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($adocoes as $adocao)
                                    <tr>
                                        <td><span class="text-muted fw-bold">#{{ str_pad($adocao->id, 4, '0', STR_PAD_LEFT) }}</span></td>
                                        <td>
                                            @if($adocao->pet)
                                                <a href="{{ route('admin.pets.show', $adocao->pet->id) }}" class="text-decoration-none fw-bold"><i class="bi bi-suit-heart text-danger me-1"></i> {{ $adocao->pet->nome }}</a>
                                            @else
                                                <span class="badge bg-danger">Pet Excluído</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($adocao->user)
                                                <a href="{{ route('admin.users.show', $adocao->user->id) }}" class="text-decoration-none">{{ $adocao->user->name }}</a>
                                            @else
                                                <span class="text-muted">Conta Excluída</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($adocao->pet && $adocao->pet->user)
                                                <a href="{{ route('admin.users.show', $adocao->pet->user->id) }}" class="text-decoration-none">{{ $adocao->pet->user->name }}</a>
                                            @else
                                                <span class="text-muted">Desconhecido</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($adocao->status === 'aprovado' || $adocao->status === 'concluido')
                                                <span class="badge bg-success bg-opacity-10 text-success border border-success px-2 py-1"><i class="bi bi-check-circle me-1"></i> {{ ucfirst($adocao->status) }}</span>
                                            @elseif($adocao->status === 'rejeitado' || $adocao->status === 'cancelado')
                                                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger px-2 py-1"><i class="bi bi-x-circle me-1"></i> {{ ucfirst($adocao->status) }}</span>
                                            @else
                                                <span class="badge bg-warning bg-opacity-10 text-dark border border-warning px-2 py-1"><i class="bi bi-clock me-1"></i> {{ ucfirst($adocao->status) }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $adocao->created_at->format('d/m/Y') }}</td>
                                        <td>
                                            <a href="{{ route('admin.adoptions.show', $adocao->id) }}" class="btn btn-sm btn-outline-info rounded-pill px-3">
                                                Detalhes
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4 text-muted">Nenhuma solicitação de adoção encontrada.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4 d-flex justify-content-center">
                        {{ $adocoes->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection