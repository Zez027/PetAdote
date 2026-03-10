@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="mb-4 text-danger fw-bold">
        <i class="bi bi-heart-fill"></i> Meus Favoritos
    </h2>

    @if($pets->isEmpty())
        <div class="alert alert-light text-center py-5 shadow-sm">
            <h4 class="text-muted">Você ainda não tem favoritos 💔</h4>
            <p>Explore a lista de pets e clique no coração para salvar os que você mais gostou.</p>
            <a href="{{ route('home') }}" class="btn btn-primary mt-3">Encontrar um Amigo</a>
        </div>
    @else
        <div class="row">
            @foreach($pets as $pet)
                <div class="col-md-3 mb-4">
                    <div class="card h-100 shadow-sm border-0">
                        {{-- Foto do Pet --}}
                        <div class="position-relative">
                            @if($pet->photos->first())
                                <img src="{{ asset('storage/' . $pet->photos->first()->foto) }}" 
                                     class="card-img-top img-padrao-home" 
                                     alt="{{ $pet->nome }}">
                            @else
                                <img src="{{ asset('images/sem-foto.png') }}" 
                                     class="card-img-top img-padrao-home" 
                                     alt="Sem foto">
                            @endif
                            
                            {{-- Badge de Status --}}
                            <span class="position-absolute top-0 end-0 badge bg-light text-dark m-2 shadow-sm">
                                {{ ucfirst($pet->porte) }}
                            </span>
                        </div>

                        <div class="card-body">
                            <h5 class="card-title fw-bold text-dark">{{ $pet->nome }}</h5>
                            <p class="card-text text-muted small">
                                <i class="bi bi-geo-alt-fill text-danger"></i> 
                                {{ $pet->cidade }} - {{ $pet->estado }}
                            </p>
                            
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <a href="{{ route('pets.show', $pet->id) }}" class="btn btn-sm btn-outline-primary">
                                    Ver Detalhes
                                </a>

                                {{-- Botão Remover Favorito (Mini Form) --}}
                                <form action="{{ route('pets.favorite', $pet->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-danger" title="Remover dos favoritos">
                                        <i class="bi bi-trash"></i> Remover
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
    <div class="mt-4 d-flex justify-content-center">
        {{ $pets->links() }}
    </div>
</div>

<style>
     /* Customização da Paginação PetAdote */
    .pagination {
        gap: 5px; /* Espaço entre os números */
    }

    .pagination .page-item .page-link {
        border-radius: 10px !important; /* Botões arredondados */
        border: none;
        color: #0d6efd; /* Cor azul do seu projeto */
        padding: 8px 16px;
        background-color: #f8f9fa;
        transition: all 0.3s ease;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }

    .pagination .page-item.active .page-link {
        background-color: #0d6efd !important; /* Azul escuro para página ativa */
        color: white !important;
        font-weight: bold;
        box-shadow: 0 4px 8px rgba(13, 110, 253, 0.3);
    }

    .pagination .page-item .page-link:hover {
        background-color: #e9ecef;
        transform: translateY(-2px); /* Efeito de levante */
        color: #0a58ca;
    }

    /* Esconder o texto "Showing X to Y of Z" para ficar mais minimalista */
    .pagination-wrapper nav > div:first-child {
        display: none !important;
    }
</style>
@endsection