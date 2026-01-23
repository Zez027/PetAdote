@extends('layouts.app')

@section('content')
    <h1 class="mb-4">Meus Pets</h1>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @auth
        @if($pets->isEmpty())
            <div class="alert alert-info">Você não possui pets cadastrados.</div>
        @else
            <div class="row">
                @foreach($pets as $pet)
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 shadow-sm rounded">

                            @php
                                // Obtém a foto principal
                                $fotoPrincipal = $pet->photos->where('is_main', true)->first();
                                
                                // Se não existir principal, usa a primeira
                                if (!$fotoPrincipal && $pet->photos->count() > 0) {
                                    $fotoPrincipal = $pet->photos->first();
                                }
                            @endphp

                            @if($fotoPrincipal)
                                <img
                                    src="{{ asset('storage/' . $fotoPrincipal->foto) }}"
                                    class="card-img-top"
                                    alt="Foto do pet {{ $pet->nome }}"
                                    style="height: 220px; object-fit: cover;">
                            @else
                                <img
                                    src="https://via.placeholder.com/400x250?text=Sem+foto"
                                    class="card-img-top"
                                    alt="Sem foto">
                            @endif

                            <div class="card-body">
                                <h5 class="card-title">{{ $pet->nome }}</h5>
                                <p class="card-text">
                                    <strong>Idade:</strong> {{ $pet->idade }} anos<br>
                                    <strong>Gênero:</strong> {{ ucfirst($pet->genero) }}<br>
                                    <strong>Porte:</strong> {{ ucfirst($pet->porte) }}<br>
                                    <strong>Raça:</strong> {{ $pet->raca }}<br>
                                    <strong>Tipo:</strong> {{ ucfirst($pet->tipo) }}<br>

                                    <strong>Status:</strong>
                                    <span class="badge bg-{{ $pet->status == 'disponivel' ? 'success' : ($pet->status == 'adotado' ? 'secondary' : 'warning') }}">
                                        {{ ucfirst($pet->status) }}
                                    </span>

                                    @if($pet->descricao)
                                        <br><strong>Descrição:</strong> {{ $pet->descricao }}
                                    @endif
                                </p>
                            </div>

                            <div class="card-footer d-flex justify-content-between">
                                <a href="{{ route('pets.edit', $pet) }}" class="btn btn-sm btn-warning">Editar</a>

                                <form action="{{ route('pets.destroy', $pet) }}" method="POST"
                                      onsubmit="return confirm('Deseja realmente excluir este pet?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        Excluir
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    @else
        <div class="alert alert-warning">Você precisa estar logado para ver seus pets.</div>
    @endauth
@endsection
