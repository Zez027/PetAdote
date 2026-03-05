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
                    
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nome</th>
                                    <th>E-mail</th>
                                    <th>Perfil</th>
                                    <th>Data de Cadastro</th>
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
                                        <td>{{ $user->created_at->format('d/m/Y') }}</td>
                                        <td>
                                            <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-sm btn-outline-info" title="Ver Detalhes">
                                                Visualizar
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">Nenhum usuário encontrado.</td>
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