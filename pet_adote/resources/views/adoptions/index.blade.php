@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-dark">
            <i class="bi bi-clipboard-check text-primary"></i> Solicitações de Adoção
        </h2>
    </div>

    @if($requests->isEmpty())
        <div class="card border-0 shadow-sm rounded-4 p-5 text-center">
            <div class="mb-3">
                <i class="bi bi-inbox text-muted" style="font-size: 3rem;"></i>
            </div>
            <h4 class="text-secondary">Nenhuma solicitação por enquanto.</h4>
            <p class="text-muted">Quando alguém se interessar pelos seus pets, as solicitações aparecerão aqui.</p>
        </div>
    @else
        <div class="row g-4">
            @foreach($requests as $request)
                <div class="col-12">
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                        <div class="row g-0">
                            {{-- Foto do Pet --}}
                            <div class="col-md-2 bg-light d-flex align-items-center justify-content-center p-3">
                                @php
                                    $foto = $request->pet->photos->where('is_main', true)->first() ?? $request->pet->photos->first();
                                @endphp
                                <img src="{{ $foto ? asset('storage/' . $foto->foto) : asset('images/sem-foto.png') }}" 
                                     class="rounded-circle border border-3 border-white shadow-sm" 
                                     width="100" height="100" style="object-fit: cover;">
                            </div>

                            {{-- Info do Pedido --}}
                            <div class="col-md-6 p-4">
                                <h5 class="fw-bold mb-1">
                                    <span class="text-primary">{{ $request->user->name }}</span> 
                                    quer adotar 
                                    <span class="text-success">{{ $request->pet->nome }}</span>
                                </h5>
                                <p class="text-muted small mb-3">
                                    Solicitado em: {{ $request->created_at->format('d/m/Y \à\s H:i') }}
                                </p>
                                
                                {{-- Status Atual --}}
                                @if($request->status == 'pendente')
                                    <span class="badge bg-warning-subtle text-warning border border-warning px-3 rounded-pill">Aguardando sua decisão</span>
                                @elseif($request->status == 'aprovado')
                                    <span class="badge bg-success-subtle text-success border border-success px-3 rounded-pill">Adoção Aprovada</span>
                                @else
                                    <span class="badge bg-danger-subtle text-danger border border-danger px-3 rounded-pill">Solicitação Rejeitada</span>
                                @endif
                            </div>

                            {{-- Ações --}}
                            <div class="col-md-4 p-4 d-flex align-items-center justify-content-md-end gap-2 bg-light-subtle">
                                @if($request->status == 'pendente')
                                    {{-- Botão Aprovar --}}
                                    <form action="{{ route('adoptions.approve', $request->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-success rounded-pill fw-bold px-4 shadow-sm">
                                            <i class="bi bi-check-lg me-1"></i> Aprovar
                                        </button>
                                    </form>

                                    {{-- Botão Rejeitar --}}
                                    <form action="{{ route('adoptions.reject', $request->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-danger rounded-pill fw-bold px-4" 
                                                onclick="return confirm('Tem certeza que deseja rejeitar esta solicitação?')">
                                            Rejeitar
                                        </button>
                                    </form>
                                @elseif($request->status == 'aprovado')
                                    <a href="https://wa.me/55{{ preg_replace('/\D/', '', $request->user->contato) }}" 
                                       target="_blank" class="btn btn-outline-success rounded-pill fw-bold px-4">
                                        <i class="bi bi-whatsapp me-1"></i> Contatar Adotante
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection