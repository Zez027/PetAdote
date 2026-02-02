@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-secondary">Solicitações Recebidas</h2>
        <span class="badge bg-primary px-3 py-2">{{ $requests->total() }} pedidos no total</span>
    </div>

    @include('layouts.partials.alerts') {{-- Centralize seus alertas de success/error aqui --}}

    <div class="row">
        @forelse($requests as $request)
            <div class="col-md-12 mb-3">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <div class="row align-items-center">
                            <div class="col-md-4">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        @if($request->pet->photos->where('is_main', true)->first())
                                            <img src="{{ asset('storage/' . $request->pet->photos->where('is_main', true)->first()->photo_path) }}" class="rounded shadow-sm" style="width: 80px; height: 80px; object-fit: cover;">
                                        @else
                                            <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                                                <i class="fas fa-paw text-muted"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="ms-3">
                                        <h5 class="mb-0 fw-bold">{{ $request->pet->name }}</h5>
                                        <small class="text-muted">{{ $request->pet->breed }}</small>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4 border-start border-end text-center">
                                <p class="mb-0 text-muted small text-uppercase fw-bold">Interessado</p>
                                <p class="mb-0 fw-bold text-dark">{{ $request->user->name }}</p>
                                <small class="text-muted">Pedido em {{ $request->created_at->format('d/m/Y') }}</small>
                            </div>

                            <div class="col-md-4 text-end">
                                <div class="d-flex flex-column align-items-end">
                                    <span class="badge mb-3 px-3 py-2 rounded-pill 
                                        @if($request->status == 'aprovado') bg-success 
                                        @elseif($request->status == 'rejeitado') bg-danger 
                                        @else bg-warning text-dark @endif">
                                        {{ ucfirst($request->status) }}
                                    </span>

                                    @can('update', $request)
                                        @if($request->status == 'pendente')
                                            <div class="btn-group w-100 shadow-sm" role="group">
                                                <form action="{{ route('adoptions.approve', $request) }}" method="POST" class="w-100">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-outline-success border-0 w-100 fw-bold">Aprovar</button>
                                                </form>
                                                <form action="{{ route('adoptions.reject', $request) }}" method="POST" class="w-100 border-start">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-outline-danger border-0 w-100 fw-bold">Rejeitar</button>
                                                </form>
                                            </div>
                                        @endif
                                    @endcan
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <p class="text-muted">Nenhuma solicitação encontrada.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-4 d-flex justify-content-center">
        {{ $requests->links() }}
    </div>
</div>
@endsection