@extends('layouts.app')

@section('content')
<div class="container-fluid mt-4 px-xl-5">
    <div class="row">
        <div class="col-md-2 mb-4">
            <div class="bg-white rounded-3 shadow-sm border-0 h-100 p-3 sticky-top" style="top: 20px; z-index: 100;">
                <h6 class="text-uppercase text-muted fw-bold mb-3 small ms-2 mt-2">Painel Admin</h6>
                <ul class="nav flex-column nav-pills gap-1">
                    <li class="nav-item">
                        <a class="nav-link text-dark" href="{{ route('admin.dashboard') }}"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active bg-primary" href="{{ route('admin.users.index') }}"><i class="bi bi-people me-2"></i> Usuários</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-dark" href="{{ route('admin.pets.index') }}"><i class="bi bi-suit-heart me-2"></i> Pets</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-dark" href="{{ route('admin.adoptions.index') }}"><i class="bi bi-clipboard2-heart me-2"></i> Adoções</a>
                    </li>
                </ul>
            </div>
        </div>
        
        <div class="col-md-10">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="h3 mb-0 text-dark fw-bold">Detalhes do Usuário</h2>
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary rounded-pill px-4"><i class="bi bi-arrow-left me-1"></i> Voltar</a>
            </div>

            @if(session('success'))
                <div class="alert alert-success shadow-sm rounded-3">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger shadow-sm rounded-3">{{ session('error') }}</div>
            @endif

            <div class="card shadow-sm border-0 rounded-3 mb-4">
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-md-6 mb-4 mb-md-0 border-end">
                            <h5 class="text-primary fw-bold mb-4"><i class="bi bi-person-badge me-2"></i>Informações Principais</h5>
                            
                            <div class="d-flex align-items-center mb-4">
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3 fs-3 fw-bold" style="width: 70px; height: 70px;">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <h4 class="mb-0 fw-bold">{{ $user->name }}</h4>
                                    <span class="text-muted">ID: #{{ $user->id }}</span>
                                </div>
                            </div>

                            <p class="mb-2"><i class="bi bi-envelope text-muted me-2"></i> <strong>E-mail:</strong> {{ $user->email }}</p>
                            <p class="mb-2"><i class="bi bi-calendar3 text-muted me-2"></i> <strong>Data de Cadastro:</strong> {{ $user->created_at->format('d/m/Y H:i') }}</p>
                            
                            <div class="mt-4">
                                <p class="mb-2"><strong>Tipo de Perfil:</strong> 
                                    @if($user->role === 'admin')
                                        <span class="badge bg-danger ms-2">Administrador</span>
                                    @else
                                        <span class="badge bg-secondary ms-2">Usuário Padrão</span>
                                    @endif
                                </p>
                                <p class="mb-0"><strong>Status da Conta:</strong> 
                                    @if($user->is_suspended)
                                        <span class="badge bg-warning text-dark border border-warning ms-2">Suspensa</span>
                                    @else
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success ms-2">Ativa</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                        
                        <div class="col-md-6 px-md-4">
                            <h5 class="text-primary fw-bold mb-4"><i class="bi bi-journal-text me-2"></i>Perfil de Adotante</h5>
                            <div class="p-3 bg-light border-0 rounded-3 h-100">
                                @if($user->adopter_profile)
                                    <p class="mb-0 text-dark lh-lg">{!! nl2br(e($user->adopter_profile)) !!}</p>
                                @else
                                    <div class="d-flex flex-column align-items-center justify-content-center h-100 text-muted">
                                        <i class="bi bi-file-earmark-x fs-1 mb-2"></i>
                                        <em>Este usuário ainda não preencheu o perfil.</em>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-danger border-opacity-25 shadow-sm rounded-3 mt-4">
                <div class="card-header bg-danger bg-opacity-10 border-bottom-0 py-3 rounded-top-3">
                    <h5 class="text-danger mb-0 fw-bold"><i class="bi bi-exclamation-triangle-fill me-2"></i>Zona de Perigo</h5>
                </div>
                <div class="card-body p-4 d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="fw-bold mb-1">Alterar Status de Acesso</h6>
                        <p class="text-muted small mb-0">Suspender impede o usuário de fazer login e interagir na plataforma.</p>
                    </div>
                    
                    <form id="form-suspensao" action="{{ route('admin.users.suspend', $user->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        @if($user->is_suspended)
                            <button type="button" class="btn btn-success px-4" onclick="confirmarAcao('reativar')">
                                <i class="bi bi-unlock-fill me-1"></i> Reativar Usuário
                            </button>
                        @else
                            <button type="button" class="btn btn-danger px-4" onclick="confirmarAcao('suspender')">
                                <i class="bi bi-lock-fill me-1"></i> Suspender Usuário
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