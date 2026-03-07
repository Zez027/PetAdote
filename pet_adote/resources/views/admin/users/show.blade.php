@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-header bg-primary text-white">Menu Administrador</div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="list-group-item bg-light"><a href="{{ route('admin.users.index') }}">Gerenciar Usuários</a></li>
                    <li class="list-group-item"><a href="{{ route('admin.pets.index') }}">Gerenciar Pets</a></li>
                    <li class="list-group-item"><a href="{{ route('admin.adoptions.index') }}">Adoções</a></li>
                </ul>
            </div>
        </div>
        
        <div class="col-md-9">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h2 class="mb-0">Detalhes do Usuário</h2>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary btn-sm">Voltar</a>
                </div>
                <div class="card-body">
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5 class="text-muted">Informações Principais</h5>
                            <p><strong>ID:</strong> {{ $user->id }}</p>
                            <p><strong>Nome:</strong> {{ $user->name }}</p>
                            <p><strong>E-mail:</strong> {{ $user->email }}</p>
                            <p><strong>Data de Cadastro:</strong> {{ $user->created_at->format('d/m/Y H:i') }}</p>
                            <p><strong>Tipo de Perfil:</strong> 
                                @if($user->role === 'admin')
                                    <span class="badge bg-danger">Administrador</span>
                                @else
                                    <span class="badge bg-secondary">Usuário Padrão</span>
                                @endif
                            </p>
                            <p><strong>Status da Conta:</strong> 
                                @if($user->is_suspended)
                                    <span class="badge bg-danger">Suspensa</span>
                                @else
                                    <span class="badge bg-success">Ativa</span>
                                @endif
                            </p>
                        </div>
                        
                        <div class="col-md-6">
                            <h5 class="text-muted">Perfil de Adotante</h5>
                            <div class="p-3 bg-light border rounded">
                                @if($user->adopter_profile)
                                    <p class="mb-0">{!! nl2br(e($user->adopter_profile)) !!}</p>
                                @else
                                    <p class="mb-0 text-muted"><em>Este usuário ainda não preencheu o perfil de adotante.</em></p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <hr>
                    <h5 class="text-danger">Ações Administrativas</h5>

                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <form id="form-suspensao" action="{{ route('admin.users.suspend', $user->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        @if($user->is_suspended)
                            <button type="button" class="btn btn-success" onclick="confirmarAcao('reativar')">
                                Reativar Usuário
                            </button>
                        @else
                            <button type="button" class="btn btn-danger" onclick="confirmarAcao('suspender')">
                                Suspender Usuário
                            </button>
                        @endif
                    </form>
                    
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmarAcao(acao) {
        const titulo = acao === 'suspender' ? 'Suspender Usuário?' : 'Reativar Usuário?';
        const texto = acao === 'suspender' 
            ? "Este usuário será desconectado e não poderá acessar o sistema." 
            : "O usuário terá seu acesso restaurado normalmente.";
        const corBotao = acao === 'suspender' ? '#dc3545' : '#198754'; 
        const textoBotao = acao === 'suspender' ? 'Sim, suspender' : 'Sim, reativar';

        Swal.fire({
            title: titulo,
            text: texto,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: corBotao,
            cancelButtonColor: '#6c757d',
            confirmButtonText: textoBotao,
            cancelButtonText: 'Cancelar',
            reverseButtons: true 
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('form-suspensao').submit();
            }
        });
    }
</script>
@endsection