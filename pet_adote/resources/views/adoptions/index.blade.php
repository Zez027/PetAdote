@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="mb-4 text-success fw-bold">Solicitações de Adoção</h2>

    @if($requests->isEmpty())
        <div class="alert alert-info">
            Você ainda não recebeu nenhuma solicitação de adoção.
        </div>
    @else
        <div class="card shadow-sm border-0 rounded-3">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="p-3">Interessado</th>
                                <th>Pet</th>
                                <th>Contato</th>
                                <th>Data</th>
                                <th>Status</th>
                                <th class="text-end p-3">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($requests as $req)
                            <tr>
                                <td class="p-3">
                                    <span class="fw-bold">{{ $req->user->name }}</span><br>
                                    <small class="text-muted">{{ $req->user->cidade }} - {{ $req->user->estado }}</small>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        {{-- Miniatura da foto --}}
                                        @if($req->pet->photos->first())
                                            <img src="{{ asset('storage/' . $req->pet->photos->first()->foto) }}" 
                                                 class="rounded-circle me-2" width="40" height="40" style="object-fit: cover;">
                                        @endif
                                        <a href="{{ route('pets.show', $req->pet->id) }}" class="text-decoration-none text-dark fw-bold">
                                            {{ $req->pet->nome }}
                                        </a>
                                    </div>
                                </td>
                                <td>
                                    {{-- Botão de WhatsApp --}}
                                    @if($req->user->contato)
                                        <a href="https://wa.me/55{{ preg_replace('/\D/', '', $req->user->contato) }}" target="_blank" class="btn btn-sm btn-outline-success">
                                            <i class="bi bi-whatsapp"></i> Conversar
                                        </a>
                                    @else
                                        <span class="text-muted">Sem contato</span>
                                    @endif
                                </td>
                                <td>{{ $req->created_at->format('d/m/Y') }}</td>
                                <td>
                                    @if($req->status == 'pendente')
                                        <span class="badge bg-warning text-dark">Pendente</span>
                                    @elseif($req->status == 'aprovado')
                                        <span class="badge bg-success">Aprovado</span>
                                    @else
                                        <span class="badge bg-danger">Rejeitado</span>
                                    @endif
                                </td>
                                <td class="text-end p-3">
                                    @if($req->status == 'pendente')
                                        <div class="d-flex gap-1 justify-content-end">
                                            {{-- Form Aprovar --}}
                                            <form action="{{ route('adoptions.approve', $req->id) }}" method="POST">
                                                @csrf
                                                <button class="btn btn-sm btn-success" title="Aprovar">
                                                    <i class="bi bi-check-lg"></i>
                                                </button>
                                            </form>

                                            {{-- Form Rejeitar --}}
                                            <form action="{{ route('adoptions.reject', $req->id) }}" method="POST">
                                                @csrf
                                                <button class="btn btn-sm btn-danger" title="Rejeitar">
                                                    <i class="bi bi-x-lg"></i>
                                                </button>
                                            </form>
                                        </div>
                                    @else
                                        <span class="text-muted small">Finalizado</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection