@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-header bg-primary text-white">Menu Administrador</div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="list-group-item"><a href="{{ route('admin.users.index') }}">Gerenciar Usuários</a></li>
                    <li class="list-group-item bg-light"><a href="{{ route('admin.pets.index') }}">Gerenciar Pets</a></li>
                    <li class="list-group-item text-muted">Adoções (Em breve)</li>
                </ul>
            </div>
        </div>
        
        <div class="col-md-9">
            <div class="card">
                <div class="card-header">
                    <h2 class="mb-0">Gestão de Pets</h2>
                </div>
                <div class="card-body">
                    
                    <form action="{{ route('admin.pets.index') }}" method="GET" class="mb-4 bg-light p-3 rounded border">
                        <div class="row g-2">
                            <div class="col-md-4">
                                <label class="form-label small text-muted mb-1">Buscar</label>
                                <input type="text" name="search" class="form-control" placeholder="Nome do pet ou doador..." value="{{ request('search') }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small text-muted mb-1">Espécie</label>
                                <select name="species" class="form-select">
                                    <option value="">Todas</option>
                                    <option value="Cachorro" {{ request('species') == 'Cachorro' ? 'selected' : '' }}>Cachorro</option>
                                    <option value="Gato" {{ request('species') == 'Gato' ? 'selected' : '' }}>Gato</option>
                                    <option value="Outro" {{ request('species') == 'Outro' ? 'selected' : '' }}>Outro</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small text-muted mb-1">Status</label>
                                <select name="status" class="form-select">
                                    <option value="">Todos</option>
                                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Ativos</option>
                                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inativos/Deletados</option>
                                </select>
                            </div>
                            <div class="col-md-2 d-flex align-items-end gap-2">
                                <button type="submit" class="btn btn-primary w-100">Filtrar</button>
                                <a href="{{ route('admin.pets.index') }}" class="btn btn-outline-secondary" title="Limpar"><i class="bi bi-eraser"></i></a>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-striped table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nome do Pet</th>
                                    <th>Espécie</th>
                                    <th>Doador</th>
                                    <th>Status</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pets as $pet)
                                    <tr>
                                        <td>{{ $pet->id }}</td>
                                        <td><strong>{{ $pet->nome }}</strong></td>
                                        <td>{{ $pet->tipo }}</td>
                                        <td>{{ $pet->user->name ?? 'Usuário Desconhecido' }}</td>
                                        <td>
                                            @if($pet->trashed())
                                                <span class="badge bg-danger">Inativo</span>
                                            @else
                                                <span class="badge bg-success">Ativo</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.pets.show', $pet->id) }}" class="btn btn-sm btn-outline-info" title="Ver Detalhes">Visualizar</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4 text-muted">Nenhum pet encontrado.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $pets->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection