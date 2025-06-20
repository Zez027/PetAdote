@extends('layouts.app')

@section('content')
    <h1>Pets disponíveis para adoção</h1>

    @if($pets->isEmpty())
        <p>Nenhum pet cadastrado ainda.</p>
    @else
        <div class="row">
            @foreach($pets as $pet)
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        @if($pet->photos->first())
                            <img src="{{ asset('storage/' . $pet->photos->first()->foto) }}" class="card-img-top" alt="Foto do pet">
                        @else
                            <img src="https://via.placeholder.com/400x250?text=Sem+foto" class="card-img-top" alt="Sem foto">
                        @endif
                        <div class="card-body">
                            <h5 class="card-title">{{ $pet->nome }}</h5>
                            <p class="card-text">
                                <strong>Raça:</strong> {{ $pet->raca }}<br>
                                <strong>Idade:</strong> {{ $pet->idade }} anos<br>
                                <strong>Status:</strong> {{ ucfirst($pet->status) }}
                            </p>
                            <a href="{{ route('pets.show', $pet) }}" class="btn btn-primary btn-sm">Ver detalhes</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
@endsection
