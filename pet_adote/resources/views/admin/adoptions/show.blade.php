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
                        <a class="nav-link text-dark" href="{{ route('admin.pets.index') }}"><i class="bi bi-suit-heart me-2"></i> Pets</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active bg-primary" href="{{ route('admin.adoptions.index') }}"><i class="bi bi-clipboard2-heart me-2"></i> Adoções</a>
                    </li>
                </ul>
            </div>
        </div>
        
        <div class="col-md-10">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="h3 mb-0 text-dark fw-bold">
                    <i class="bi bi-clipboard2-heart text-danger me-2"></i>Adoção de <span class="text-primary">{{ $adocao->pet ? $adocao->pet->nome : 'Pet Excluído' }}</span>
                    <span class="text-muted fs-5 ms-2 fw-normal">#{{ str_pad($adocao->id, 4, '0', STR_PAD_LEFT) }}</span>
                </h2>
                <a href="{{ route('admin.adoptions.index') }}" class="btn btn-outline-secondary rounded-pill px-4"><i class="bi bi-arrow-left me-1"></i> Voltar</a>
            </div>

            <div class="card shadow-sm border-0 rounded-3 mb-4 
                @if($adocao->status === 'aprovado' || $adocao->status === 'concluido') bg-success bg-opacity-10 border-start border-success border-4
                @elseif($adocao->status === 'rejeitado' || $adocao->status === 'cancelado') bg-danger bg-opacity-10 border-start border-danger border-4
                @else bg-warning bg-opacity-10 border-start border-warning border-4 @endif">
                
                <div class="card-body p-4 d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-1 fw-bold 
                            @if($adocao->status === 'aprovado' || $adocao->status === 'concluido') text-success 
                            @elseif($adocao->status === 'rejeitado' || $adocao->status === 'cancelado') text-danger 
                            @else text-dark @endif">
                            Status: {{ ucfirst($adocao->status) }}
                        </h4>
                        <p class="mb-0 small text-muted"><i class="bi bi-calendar-event me-1"></i> Solicitado em: {{ $adocao->created_at->format('d/m/Y \à\s H:i') }}</p>
                    </div>
                    
                    @if($adocao->status === 'aprovado' || $adocao->status === 'concluido')
                        <a href="{{ route('adoptions.contract', $adocao->id) }}" class="btn btn-success shadow-sm rounded-pill px-4" target="_blank">
                            <i class="bi bi-file-earmark-pdf me-1"></i> Ver Contrato
                        </a>
                    @endif
                </div>
            </div>

            @if($adocao->status === 'rejeitado' && !empty($adocao->motivo_rejeicao))
                <div class="alert alert-danger shadow-sm border-0 rounded-3 mb-4 p-4">
                    <h5 class="alert-heading fw-bold mb-2"><i class="bi bi-x-circle me-2"></i>Motivo da Rejeição</h5>
                    <p class="mb-0">{{ $adocao->motivo_rejeicao }}</p>
                </div>
            @endif

            <div class="row">
                <div class="col-md-6 mb-4">
                    <div class="card shadow-sm border-0 rounded-3 h-100">
                        <div class="card-header bg-white border-bottom py-3">
                            <h5 class="mb-0 fw-bold text-primary"><i class="bi bi-person-up me-2"></i>O Adotante</h5>
                        </div>
                        <div class="card-body p-4">
                            @if($adocao->user)
                                <h5 class="fw-bold mb-1"><a href="{{ route('admin.users.show', $adocao->user->id) }}" class="text-decoration-none">{{ $adocao->user->name }}</a></h5>
                                <p class="text-muted mb-4"><i class="bi bi-envelope me-1"></i> {{ $adocao->user->email }}</p>
                                
                                <h6 class="fw-bold text-muted small text-uppercase mb-2">Perfil do Adotante</h6>
                                <div class="bg-light p-3 rounded-3 border-0">
                                    @if($adocao->user->adopter_profile)
                                        <p class="mb-0 small lh-lg">{{ $adocao->user->adopter_profile }}</p>
                                    @else
                                        <em class="text-muted small">Perfil não preenchido.</em>
                                    @endif
                                </div>
                            @else
                                <div class="alert alert-danger mb-0">A conta do adotante foi apagada do sistema.</div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-md-6 mb-4">
                    <div class="card shadow-sm border-0 rounded-3 h-100">
                        <div class="card-header bg-white border-bottom py-3">
                            <h5 class="mb-0 fw-bold text-info"><i class="bi bi-suit-heart-fill me-2"></i>O Pet e o Doador</h5>
                        </div>
                        <div class="card-body p-4">
                            <h6 class="fw-bold text-muted small text-uppercase mb-2">Animal Escolhido</h6>
                            @if($adocao->pet)
                                <div class="d-flex align-items-center mb-4 bg-light p-3 rounded-3">
                                    @if($adocao->pet->photos->count() > 0)
                                        <img src="{{ asset('storage/' . $adocao->pet->photos->first()->foto) }}" class="rounded-circle object-fit-cover shadow-sm me-3" width="60" height="60">
                                    @else
                                        <div class="bg-secondary bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 60px; height: 60px;">
                                            <i class="bi bi-camera text-muted"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <h5 class="fw-bold mb-1"><a href="{{ route('admin.pets.show', $adocao->pet->id) }}" class="text-decoration-none text-dark">{{ $adocao->pet->nome }}</a></h5>
                                        <p class="mb-0 small text-muted">{{ $adocao->pet->tipo }} • {!! $adocao->pet->trashed() ? '<span class="text-danger fw-bold">Anúncio Inativo</span>' : '<span class="text-success fw-bold">Anúncio Ativo</span>' !!}</p>
                                    </div>
                                </div>
                                
                                <h6 class="fw-bold text-muted small text-uppercase mb-2">Responsável (Doador)</h6>
                                @if($adocao->pet->user)
                                    <div class="ps-3 border-start border-3 border-info">
                                        <p class="fw-bold mb-1"><a href="{{ route('admin.users.show', $adocao->pet->user->id) }}" class="text-decoration-none">{{ $adocao->pet->user->name }}</a></p>
                                        <p class="text-muted small mb-0"><i class="bi bi-envelope me-1"></i> {{ $adocao->pet->user->email }}</p>
                                    </div>
                                @else
                                    <p class="text-danger small mb-0">A conta do doador foi apagada.</p>
                                @endif
                            @else
                                <div class="alert alert-danger mb-0">O registo deste pet foi permanentemente excluído.</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            @if(isset($adocao->mensagem) && !empty($adocao->mensagem))
                <div class="card shadow-sm border-0 rounded-3 mb-4">
                    <div class="card-header bg-white border-bottom py-3">
                        <h5 class="mb-0 fw-bold text-dark"><i class="bi bi-chat-left-text text-primary me-2"></i>Mensagem do Adotante</h5>
                    </div>
                    <div class="card-body p-4">
                        <p class="mb-0 fst-italic lh-lg text-dark">"{!! nl2br(e($adocao->mensagem)) !!}"</p>
                    </div>
                </div>
            @endif

        </div>
    </div>
</div>
@endsection