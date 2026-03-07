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
                <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center py-3">
                        <h3 class="mb-0">
                            <i class="bi bi-clipboard2-heart text-danger me-2"></i>
                            Adoção de <span class="text-primary">{{ $adocao->pet ? $adocao->pet->nome : 'Pet Excluído' }}</span>
                            <span class="text-muted fs-5 ms-2 fw-normal">#{{ str_pad($adocao->id, 4, '0', STR_PAD_LEFT) }}</span>
                        </h3>
                        <a href="{{ route('admin.adoptions.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-arrow-left"></i> Voltar
                        </a>
                </div>
                <div class="card-body">
                    
                    <div class="alert 
                        @if($adocao->status === 'aprovado' || $adocao->status === 'concluido') alert-success 
                        @elseif($adocao->status === 'rejeitado' || $adocao->status === 'cancelado') alert-danger 
                        @else alert-warning @endif
                        d-flex justify-content-between align-items-center">
                        
                        <div>
                            <h4 class="mb-1">Status: <strong>{{ ucfirst($adocao->status) }}</strong></h4>
                            <p class="mb-0 small">Solicitado em: {{ $adocao->created_at->format('d/m/Y \à\s H:i') }}</p>
                        </div>
                        
                        @if($adocao->status === 'aprovado' || $adocao->status === 'concluido')
                            <a href="{{ route('adoptions.contract', $adocao->id) }}" class="btn btn-outline-success btn-sm" target="_blank">
                                <i class="bi bi-file-earmark-pdf"></i> Ver Contrato Gerado
                            </a>
                        @endif
                    </div>

                    @if($adocao->status === 'rejeitado' && !empty($adocao->motivo_rejeicao))
                        <div class="alert alert-danger mb-4">
                            <strong>Motivo da Rejeição:</strong><br>
                            {{ $adocao->motivo_rejeicao }}
                        </div>
                    @endif

                    <div class="row mt-4">
                        <div class="col-md-6 mb-4">
                            <h5 class="text-primary border-bottom pb-2">Informações do Adotante</h5>
                            @if($adocao->user)
                                <p class="mb-1"><strong>Nome:</strong> <a href="{{ route('admin.users.show', $adocao->user->id) }}">{{ $adocao->user->name }}</a></p>
                                <p class="mb-1"><strong>E-mail:</strong> {{ $adocao->user->email }}</p>
                                <div class="mt-3 p-3 bg-light rounded border">
                                    <h6 class="text-muted mb-2">Perfil de Adotante:</h6>
                                    @if($adocao->user->adopter_profile)
                                        <p class="mb-0 small">{!! nl2br(e($adocao->user->adopter_profile)) !!}</p>
                                    @else
                                        <p class="mb-0 small text-muted"><em>Não preencheu o perfil.</em></p>
                                    @endif
                                </div>
                            @else
                                <p class="text-danger">A conta do adotante foi apagada do sistema.</p>
                            @endif
                        </div>

                        <div class="col-md-6 mb-4">
                            <h5 class="text-primary border-bottom pb-2">O Pet e o Doador</h5>
                            
                            @if($adocao->pet)
                                <p class="mb-1"><strong>Pet:</strong> <a href="{{ route('admin.pets.show', $adocao->pet->id) }}">{{ $adocao->pet->nome }}</a> ({{ $adocao->pet->tipo }})</p>
                                <p class="mb-3"><strong>Status do Anúncio:</strong> {!! $adocao->pet->trashed() ? '<span class="text-danger">Inativo</span>' : '<span class="text-success">Ativo</span>' !!}</p>
                                
                                <h6 class="text-muted mt-3">Responsável pelo Pet (Doador):</h6>
                                @if($adocao->pet->user)
                                    <p class="mb-1 small"><strong>Nome:</strong> <a href="{{ route('admin.users.show', $adocao->pet->user->id) }}">{{ $adocao->pet->user->name }}</a></p>
                                    <p class="mb-1 small"><strong>E-mail:</strong> {{ $adocao->pet->user->email }}</p>
                                @else
                                    <p class="text-danger small">A conta do doador foi apagada.</p>
                                @endif
                            @else
                                <p class="text-danger">O registo deste pet foi permanentemente excluído.</p>
                            @endif
                        </div>
                    </div>

                    @if(isset($adocao->mensagem) && !empty($adocao->mensagem))
                        <hr>
                        <h5 class="text-muted mb-2">Mensagem do Adotante na Solicitação</h5>
                        <div class="p-3 bg-light border rounded mb-4">
                            {!! nl2br(e($adocao->mensagem)) !!}
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>
@endsection