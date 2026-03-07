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
                        <a class="nav-link text-dark" href="{{ route('admin.users.index') }}"><i class="bi bi-people me-2"></i> Usuários</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active bg-primary" href="{{ route('admin.pets.index') }}"><i class="bi bi-suit-heart me-2"></i> Pets</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-dark" href="{{ route('admin.adoptions.index') }}"><i class="bi bi-clipboard2-heart me-2"></i> Adoções</a>
                    </li>
                </ul>
            </div>
        </div>
        
        <div class="col-md-10">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="h3 mb-0 text-dark fw-bold">Ficha do Pet</h2>
                <a href="{{ route('admin.pets.index') }}" class="btn btn-outline-secondary rounded-pill px-4"><i class="bi bi-arrow-left me-1"></i> Voltar</a>
            </div>

            @if(session('success'))
                <div class="alert alert-success shadow-sm rounded-3">{{ session('success') }}</div>
            @endif

            <div class="card shadow-sm border-0 rounded-3 mb-4">
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-md-5 text-center mb-4 mb-md-0 border-end">
                            @if($pet->photos && $pet->photos->count() > 0)
                                @php
                                    $fotoPrincipal = $pet->photos->where('is_main', true)->first() ?? $pet->photos->first();
                                    $outrasFotos = $pet->photos->where('id', '!=', $fotoPrincipal->id);
                                @endphp

                                <div class="mb-3">
                                    <img src="{{ asset('storage/' . $fotoPrincipal->foto) }}" class="img-fluid rounded-3 shadow-sm" style="max-height: 350px; width: 100%; object-fit: cover;">
                                </div>
                                
                                @if($outrasFotos->count() > 0)
                                    <div class="d-flex justify-content-center gap-2 flex-wrap">
                                        @foreach($outrasFotos as $photo)
                                            <img src="{{ asset('storage/' . $photo->foto) }}" class="rounded-3 shadow-sm border" style="height: 70px; width: 70px; object-fit: cover;">
                                        @endforeach
                                    </div>
                                @endif
                            @else
                                <div class="bg-light rounded-3 d-flex flex-column align-items-center justify-content-center w-100" style="height: 350px;">
                                    <i class="bi bi-camera text-muted opacity-50 mb-2" style="font-size: 3rem;"></i>
                                    <span class="text-muted fw-bold">Sem fotos</span>
                                </div>
                            @endif
                        </div>
                        
                        <div class="col-md-7 px-md-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h3 class="fw-bold text-primary mb-0">{{ $pet->nome }}</h3>
                                <div>
                                    @if($pet->trashed())
                                        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger px-3 py-2"><i class="bi bi-eye-slash-fill me-1"></i> Anúncio Inativo</span>
                                    @else
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success px-3 py-2"><i class="bi bi-eye-fill me-1"></i> Anúncio Ativo</span>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="row bg-light rounded-3 p-3 mb-4 mx-0">
                                <div class="col-sm-6 mb-2">
                                    <p class="mb-1 text-muted small text-uppercase fw-bold">Espécie</p>
                                    <p class="mb-0 fw-bold">{{ $pet->tipo }}</p>
                                </div>
                                <div class="col-sm-6 mb-2">
                                    <p class="mb-1 text-muted small text-uppercase fw-bold">Raça</p>
                                    <p class="mb-0 fw-bold">{{ $pet->raca ?? 'Não informada' }}</p>
                                </div>
                                <div class="col-sm-6 mb-2">
                                    <p class="mb-1 text-muted small text-uppercase fw-bold">Idade</p>
                                    <p class="mb-0 fw-bold">{{ $pet->idade ?? 'Não informada' }}</p>
                                </div>
                                <div class="col-sm-6 mb-2">
                                    <p class="mb-1 text-muted small text-uppercase fw-bold">Gênero</p>
                                    <p class="mb-0 fw-bold">{{ $pet->genero ?? 'Não informado' }}</p>
                                </div>
                            </div>
                            
                            <h6 class="text-muted fw-bold mb-2">Descrição / História:</h6>
                            <div class="p-3 bg-white border rounded-3 text-dark lh-lg shadow-sm">
                                {!! nl2br(e($pet->descricao ?? 'Sem descrição fornecida.')) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0 rounded-3 mb-4">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0 fw-bold text-dark"><i class="bi bi-person-heart text-primary me-2"></i>Responsável pelo Pet</h5>
                </div>
                <div class="card-body p-4">
                    @if($pet->user)
                        <div class="d-flex align-items-center">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3 fs-4 fw-bold shadow-sm" style="width: 55px; height: 55px;">
                                {{ strtoupper(substr($pet->user->name, 0, 1)) }}
                            </div>
                            <div>
                                <h6 class="mb-1 fw-bold"><a href="{{ route('admin.users.show', $pet->user->id) }}" class="text-decoration-none">{{ $pet->user->name }}</a></h6>
                                <p class="mb-0 text-muted small"><i class="bi bi-envelope me-1"></i> {{ $pet->user->email }}</p>
                            </div>
                            <div class="ms-auto">
                                {!! $pet->user->is_suspended ? '<span class="badge bg-warning text-dark border border-warning">Conta Suspensa</span>' : '<span class="badge bg-success bg-opacity-10 text-success border border-success">Conta Ativa</span>' !!}
                            </div>
                        </div>
                    @else
                        <div class="alert alert-warning mb-0"><i class="bi bi-exclamation-circle me-2"></i>O usuário responsável por este pet foi apagado do sistema.</div>
                    @endif
                </div>
            </div>

            <div class="card border-danger border-opacity-25 shadow-sm rounded-3 mt-4">
                <div class="card-header bg-danger bg-opacity-10 border-bottom-0 py-3 rounded-top-3">
                    <h5 class="text-danger mb-0 fw-bold"><i class="bi bi-exclamation-triangle-fill me-2"></i>Zona de Perigo</h5>
                </div>
                <div class="card-body p-4 d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="fw-bold mb-1">Visibilidade do Anúncio</h6>
                        <p class="text-muted small mb-0">Inativar o anúncio remove o pet da lista pública de adoções.</p>
                    </div>
                    
                    <form id="form-status-pet" action="{{ route('admin.pets.toggle_status', $pet->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        @if($pet->trashed())
                            <button type="button" class="btn btn-success px-4" onclick="confirmarAcaoPet('reativar')">
                                <i class="bi bi-eye-fill me-1"></i> Reativar Anúncio
                            </button>
                        @else
                            <button type="button" class="btn btn-danger px-4" onclick="confirmarAcaoPet('inativar')">
                                <i class="bi bi-eye-slash-fill me-1"></i> Inativar Anúncio
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
    function confirmarAcaoPet(acao) {
        const titulo = acao === 'inativar' ? 'Inativar Anúncio?' : 'Reativar Anúncio?';
        const texto = acao === 'inativar' 
            ? "Este anúncio será ocultado e não aparecerá mais para adoção pública." 
            : "O anúncio deste pet voltará a ficar visível para adoção.";
        const corBotao = acao === 'inativar' ? '#dc3545' : '#198754';
        const textoBotao = acao === 'inativar' ? 'Sim, inativar' : 'Sim, reativar';

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
                document.getElementById('form-status-pet').submit();
            }
        });
    }
</script>
@endsection