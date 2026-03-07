@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-3">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">Menu Administrador</div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="list-group-item"><a href="{{ route('admin.users.index') }}">Gerenciar Usuários</a></li>
                    <li class="list-group-item"><a href="{{ route('admin.pets.index') }}">Gerenciar Pets</a></li>
                    <li class="list-group-item bg-light"><a href="{{ route('admin.adoptions.index') }}">Adoções</a></li>
                </ul>
            </div>
        </div>
        
        <div class="col-md-9">
            <div class="card">
                <div class="card-header">
                    <h2 class="mb-0">Gestão de Adoções</h2>
                </div>
                <div class="card-body">
                    
                    <form action="{{ route('admin.adoptions.index') }}" method="GET" class="mb-4 bg-light p-3 rounded border">
                        <div class="row g-2">
                            <div class="col-md-5">
                                <label class="form-label small text-muted mb-1">Buscar</label>
                                <input type="text" name="search" class="form-control" placeholder="Nome do adotante ou pet..." value="{{ request('search') }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small text-muted mb-1">Status</label>
                                <select name="status" class="form-select">
                                    <option value="">Todos os Status</option>
                                    <option value="pendente" {{ request('status') == 'pendente' ? 'selected' : '' }}>Pendente</option>
                                    <option value="aprovado" {{ request('status') == 'aprovado' ? 'selected' : '' }}>Aprovado</option>
                                    <option value="rejeitado" {{ request('status') == 'rejeitado' ? 'selected' : '' }}>Rejeitado</option>
                                    <option value="concluido" {{ request('status') == 'concluido' ? 'selected' : '' }}>Concluído</option>
                                    <option value="cancelado" {{ request('status') == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                                </select>
                            </div>
                            <div class="col-md-3 d-flex align-items-end gap-2">
                                <button type="submit" class="btn btn-primary w-100">Filtrar</button>
                                <a href="{{ route('admin.adoptions.index') }}" class="btn btn-outline-secondary" title="Limpar"><i class="bi bi-eraser"></i></a>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-striped table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>ID</th>
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
                                        <td>{{ $adocao->id }}</td>
                                        <td>
                                            @if($adocao->pet)
                                                <a href="{{ route('admin.pets.show', $adocao->pet->id) }}"><strong>{{ $adocao->pet->nome }}</strong></a>
                                            @else
                                                <span class="text-danger">Pet Excluído</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($adocao->user)
                                                <a href="{{ route('admin.users.show', $adocao->user->id) }}">{{ $adocao->user->name }}</a>
                                            @else
                                                <span class="text-danger">Usuário Excluído</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($adocao->pet && $adocao->pet->user)
                                                <a href="{{ route('admin.users.show', $adocao->pet->user->id) }}">{{ $adocao->pet->user->name }}</a>
                                            @else
                                                <span class="text-muted">Desconhecido</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($adocao->status === 'aprovado' || $adocao->status === 'concluido')
                                                <span class="badge bg-success">{{ ucfirst($adocao->status) }}</span>
                                            @elseif($adocao->status === 'rejeitado' || $adocao->status === 'cancelado')
                                                <span class="badge bg-danger">{{ ucfirst($adocao->status) }}</span>
                                            @else
                                                <span class="badge bg-warning text-dark">{{ ucfirst($adocao->status) }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $adocao->created_at->format('d/m/Y') }}</td>
                                        <td>
                                        <a href="{{ route('admin.adoptions.show', $adocao->id) }}" class="btn btn-sm btn-outline-info" title="Ver Detalhes">Detalhes</a>
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

                    <div class="mt-3">
                        {{ $adocoes->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection