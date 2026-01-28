@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h2 class="fw-bold mb-4">Meus Pedidos de Adoção</h2>

    <div class="row g-3">
        @forelse($pedidos as $pedido)
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="p-3 d-flex align-items-center">
                        <img src="{{ asset('storage/' . ($pedido->pet->photos->first()->foto ?? 'sem-foto.png')) }}" 
                             class="rounded-3 me-3" width="80" height="80" style="object-fit: cover;">
                        
                        <div class="flex-grow-1">
                            <h5 class="fw-bold mb-0">{{ $pedido->pet->nome }}</h5>
                            <span class="badge bg-{{ $pedido->status == 'aprovado' ? 'success' : ($pedido->status == 'pendente' ? 'warning' : 'danger') }}-subtle text-{{ $pedido->status == 'aprovado' ? 'success' : ($pedido->status == 'pendente' ? 'warning' : 'danger') }} rounded-pill">
                                {{ ucfirst($pedido->status) }}
                            </span>
                        </div>

                        <a href="{{ route('pets.show', $pedido->pet_id) }}" class="btn btn-primary rounded-pill px-4">
                            Ver Detalhes
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <p>Você ainda não fez nenhum pedido.</p>
        @endforelse
    </div>
</div>
@endsection