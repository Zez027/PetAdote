@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    Menu Administrador
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="list-group-item bg-light"><a href="{{ route('admin.users.index') }}">Gerenciar Usuários</a></li>
                    <li class="list-group-item text-muted">Gerenciar Pets (Em breve)</li>
                    <li class="list-group-item text-muted">Adoções (Em breve)</li>
                </ul>
            </div>
        </div>
        
        <div class="col-md-9">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h2 class="mb-0">Gestão de Usuários</h2>
                </div>
                <div class="card-body">
                    
                    <form action="{{ route('admin.users.index') }}" method="GET" class="mb-4 bg-light p-3 rounded border">
                        <div class="row g-2">
                            <div class="col-md-4">
                                <label for="search" class="form-label small text-muted mb-1">Buscar</label>
                                <input type="text" name="search" id="search" class="form-control" placeholder="Nome ou e-mail..." value="{{ request('search') }}">
                            </div>
                            <div class="col-md-3">
                                <label for="role" class="form-label small text-muted mb-1">Perfil</label>
                                <select name="role" id="role" class="form-select">
                                    <option value="">Todos</option>
                                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Administrador</option>
                                    <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>Usuário Padrão</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="status" class="form-label small text-muted mb-1">Status</label>
                                <select name="status" id="status" class="form-select">
                                    <option value="">Todos</option>
                                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Ativos</option>
                                    <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Inativos</option>
                                </select>
                            </div>
                            <div class="col-md-2 d-flex align-items-end gap-2">
                                <button type="submit" class="btn btn-primary w-100">Filtrar</button>
                                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary" title="Limpar Filtros">
                                    <i class="bi bi-eraser"></i> Limpar
                                </a>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-striped table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nome</th>
                                    <th>E-mail</th>
                                    <th>Perfil</th>
                                    <th>Status</th> <th>Cadastro</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $user)
                                    <tr>
                                        <td>{{ $user->id }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            @if($user->role === 'admin')
                                                <span class="badge bg-danger">Admin</span>
                                            @else
                                                <span class="badge bg-secondary">Usuário</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($user->is_suspended)
                                                <span class="badge bg-warning text-dark">Inativo</span>
                                            @else
                                                <span class="badge bg-success">Ativo</span>
                                            @endif
                                        </td>
                                        <td>{{ $user->created_at->format('d/m/Y') }}</td>
                                        <td>
                                            <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-sm btn-outline-info" title="Ver Detalhes">
                                                Visualizar
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4 text-muted">
                                            Nenhum usuário encontrado com estes filtros.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $users->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection