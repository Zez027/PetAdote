@extends('layouts.app')

@section('content')
<div class="container py-5">
    
    {{-- Cabeçalho --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">Meus Pets Cadastrados</h2>
            <p class="text-muted small">Gerencie os anúncios dos animais que você disponibilizou para adoção.</p>
        </div>
        <a href="{{ route('pets.create') }}" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm">
            <i class="bi bi-plus-lg me-1"></i> Cadastrar Novo Pet
        </a>
    </div>

    @if($pets->isEmpty())
        <div class="card border-0 shadow-sm rounded-4 p-5 text-center">
            <div class="mb-3">
                <i class="bi bi-plus-circle text-muted" style="font-size: 3rem;"></i>
            </div>
            <h4 class="text-secondary">Você ainda não cadastrou nenhum pet.</h4>
            <p class="text-muted">Comece agora mesmo a ajudar um amiguinho a encontrar um lar!</p>
            <div class="mt-3">
                <a href="{{ route('pets.create') }}" class="btn btn-outline-primary rounded-pill px-4">Cadastrar Pet</a>
            </div>
        </div>
    @else
        <div class="row g-3">
            @foreach($pets as $pet)
                <div class="col-12">
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden hover-card-soft">
                        <div class="row g-0 align-items-center">
                            
                            {{-- Foto do Pet --}}
                            <div class="col-md-2 p-3">
                                @php
                                    $foto = $pet->photos->where('is_main', true)->first() ?? $pet->photos->first();
                                    $urlFoto = $foto ? asset('storage/' . $foto->foto) : asset('images/sem-foto.png');
                                @endphp
                                <img src="{{ $urlFoto }}" class="rounded-4 w-100 shadow-sm" style="height: 120px; object-fit: cover;">
                            </div>

                            {{-- Informações do Pet --}}
                            <div class="col-md-6 p-3">
                                <div class="d-flex align-items-center mb-1">
                                    <h5 class="fw-bold mb-0 me-2">{{ $pet->nome }}</h5>
                                    <span class="badge bg-{{ $pet->status == 'disponivel' ? 'success' : 'warning' }}-subtle text-{{ $pet->status == 'disponivel' ? 'success' : 'warning' }} border border-{{ $pet->status == 'disponivel' ? 'success' : 'warning' }} rounded-pill small">
                                        {{ ucfirst($pet->status) }}
                                    </span>
                                </div>
                                <p class="text-muted small mb-0">
                                    <i class="bi bi-tag me-1"></i> {{ ucfirst($pet->tipo) }} • {{ $pet->raca }} • {{ ucfirst($pet->porte) }}
                                </p>
                                <p class="text-muted small">
                                    <i class="bi bi-calendar3 me-1"></i> Cadastrado em {{ $pet->created_at->format('d/m/Y') }}
                                </p>
                            </div>

                            {{-- Botões de Ação --}}
                            <div class="col-md-4 p-4 d-flex justify-content-md-end gap-2">
                                {{-- Ver --}}
                                <a href="{{ route('pets.show', $pet->id) }}" class="btn btn-light rounded-circle shadow-sm" title="Visualizar">
                                    <i class="bi bi-eye text-primary"></i>
                                </a>

                                {{-- Editar --}}
                                <a href="{{ route('pets.edit', $pet->id) }}" class="btn btn-light rounded-circle shadow-sm" title="Editar">
                                    <i class="bi bi-pencil text-warning"></i>
                                </a>

                                {{-- Excluir --}}
                                <form action="{{ route('pets.destroy', $pet->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-light rounded-circle shadow-sm" title="Excluir" 
                                            onclick="return confirm('Tem certeza que deseja excluir este anúncio?')">
                                        <i class="bi bi-trash3 text-danger"></i>
                                    </button>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<style>
    .hover-card-soft {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .hover-card-soft:hover {
        transform: scale(1.01);
        box-shadow: 0 8px 15px rgba(0,0,0,0.08) !important;
    }
    .btn-light:hover {
        background-color: #f8f9fa;
        transform: translateY(-2px);
    }
</style>
@endsection