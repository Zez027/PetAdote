@extends('layouts.app')

@section('content')
    <h1 class="mb-4">Pets Disponíveis para Adoção</h1>

    {{-- Filtros --}}
    <form method="GET" class="row g-2 mb-4">
        <div class="col-md-3">
        <input name="estado" id="estado" list="lista-estados" class="form-control" placeholder="Digite ou escolha o estado">
            <datalist id="lista-estados">
            <!-- Estados vão aqui -->
            </datalist>
        </div>

        <div class="col-md-3">
            <input name="cidade" id="cidade" list="lista-cidades" class="form-control" placeholder="Digite ou escolha a cidade">
            <datalist id="lista-cidades">
                <!-- Cidades vão aqui -->
            </datalist>
        </div>

        <div class="col-md-3">
            <select name="status" class="form-select">
                <option value="">-- Status --</option>
                @foreach($statuses as $status)
                    <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-3">
            <button class="btn btn-primary w-100">Filtrar</button>
        </div>
    </form>

    {{-- Botão de cadastro --}}
    @auth
        <a href="{{ route('pets.create') }}" class="btn btn-success mb-4">Cadastrar Novo Pet</a>
    @endauth

    {{-- Listagem --}}
    @if($pets->isEmpty())
        <div class="alert alert-info">Nenhum pet encontrado com os filtros aplicados.</div>
    @else
        <div class="row">
            @foreach($pets as $pet)
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm rounded">
                        @if($pet->photos->count() > 0)
                            <img
                                src="{{ asset('storage/' . $pet->photos->first()->foto) }}"
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

                        @auth
                        <div class="card-footer d-flex justify-content-between">
                            @if(auth()->id() === $pet->user_id)
                                <a href="{{ route('pets.edit', $pet) }}" class="btn btn-sm btn-warning">Editar</a>
                                <form action="{{ route('pets.destroy', $pet) }}" method="POST" onsubmit="return confirm('Deseja realmente excluir este pet?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Excluir</button>
                                </form>
                            @endif
                        </div>
                        @endauth
                    </div>
                </div>
            @endforeach
        </div>
    @endif
    <script src="{{ asset('js/location.js') }}"></script>
@endsection
