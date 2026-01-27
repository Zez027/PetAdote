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
</div>
@endsection