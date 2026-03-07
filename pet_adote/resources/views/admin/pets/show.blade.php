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
                    <li class="list-group-item bg-light"><a href="{{ route('admin.pets.index') }}">Gerenciar Pets</a></li>
                    <li class="list-group-item text-muted">Adoções (Em breve)</li>
                </ul>
            </div>
        </div>
        
        <div class="col-md-9">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h2 class="mb-0">Ficha do Pet</h2>
                    <a href="{{ route('admin.pets.index') }}" class="btn btn-secondary btn-sm">Voltar</a>
                </div>
                <div class="card-body">
                    
                    <div class="row mb-4">
                    <div class="col-md-4 mb-3">
                            @if($pet->photos && $pet->photos->count() > 0)
                                @php
                                    // Pega a foto principal, ou a primeira da lista se não houver principal definida
                                    $fotoPrincipal = $pet->photos->where('is_main', true)->first() ?? $pet->photos->first();
                                    // Pega as outras fotos (excluindo a principal)
                                    $outrasFotos = $pet->photos->where('id', '!=', $fotoPrincipal->id);
                                @endphp

                                <div class="text-center mb-2">
                                    <img src="{{ asset('storage/' . $fotoPrincipal->foto) }}" 
                                         alt="Foto principal de {{ $pet->nome }}" 
                                         class="img-fluid rounded border shadow-sm" 
                                         style="max-height: 250px; width: 100%; object-fit: cover;">
                                </div>
                                
                                @if($outrasFotos->count() > 0)
                                    <div class="d-flex justify-content-center gap-2 mt-2 flex-wrap">
                                        @foreach($outrasFotos as $photo)
                                            <img src="{{ asset('storage/' . $photo->foto) }}" 
                                                 class="rounded border" 
                                                 style="height: 60px; width: 60px; object-fit: cover;">
                                        @endforeach
                                    </div>
                                @endif
                            @else
                                <div class="bg-light border rounded d-flex align-items-center justify-content-center w-100" style="height: 250px;">
                                    <span class="text-muted"><i class="bi bi-camera" style="font-size: 2rem;"></i><br>Sem foto</span>
                                </div>
                            @endif
                        </div>
                        
                        <div class="col-md-8">
                            <h4 class="text-primary">{{ $pet->nome }}</h4>
                            <p><strong>Status do Anúncio:</strong> 
                                @if($pet->trashed())
                                    <span class="badge bg-danger">Inativo / Excluído</span>
                                @else
                                    <span class="badge bg-success">Ativo</span>
                                @endif
                            </p>
                            <div class="row">
                                <div class="col-sm-6">
                                    <p class="mb-1"><strong>Espécie:</strong> {{ $pet->tipo }}</p>
                                    <p class="mb-1"><strong>Raça:</strong> {{ $pet->raca ?? 'Não informada' }}</p>
                                    <p class="mb-1"><strong>Idade:</strong> {{ $pet->idade ?? 'Não informada' }}</p>
                                </div>
                                <div class="col-sm-6">
                                    <p class="mb-1"><strong>Gênero:</strong> {{ $pet->genero ?? 'Não informado' }}</p>
                                    <p class="mb-1"><strong>Porte:</strong> {{ $pet->porte ?? 'Não informado' }}</p>
                                </div>
                            </div>
                            
                            <hr>
                            <h5 class="text-muted mb-2">Descrição</h5>
                            <div class="p-3 bg-light rounded border">
                                {!! nl2br(e($pet->descricao ?? 'Sem descrição.')) !!}
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-12">
                            <h5 class="text-muted border-bottom pb-2">Informações do Doador</h5>
                            @if($pet->user)
                                <p class="mb-1"><strong>Nome:</strong> <a href="{{ route('admin.users.show', $pet->user->id) }}">{{ $pet->user->name }}</a></p>
                                <p class="mb-1"><strong>E-mail:</strong> {{ $pet->user->email }}</p>
                                <p class="mb-1"><strong>Status da Conta:</strong> 
                                    {!! $pet->user->is_suspended ? '<span class="text-danger fw-bold">Suspensa</span>' : '<span class="text-success">Ativa</span>' !!}
                                </p>
                            @else
                                <p class="text-danger">Usuário desconhecido ou deletado.</p>
                            @endif
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

                    <form id="form-status-pet" action="{{ route('admin.pets.toggle_status', $pet->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        @if($pet->trashed())
                            <button type="button" class="btn btn-success" onclick="confirmarAcaoPet('reativar')">
                                Reativar Anúncio
                            </button>
                        @else
                            <button type="button" class="btn btn-danger" onclick="confirmarAcaoPet('inativar')">
                                Inativar Anúncio
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