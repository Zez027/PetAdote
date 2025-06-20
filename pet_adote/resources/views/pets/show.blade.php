@extends('layouts.app')

@section('content')
    <h1>{{ $pet->nome }}</h1>
    <p>{{ $pet->idade }} anos – {{ ucfirst($pet->porte) }} – {{ $pet->raca }}</p>
    <p>{{ $pet->descricao }}</p>
    <div class="mb-3">
        @foreach($pet->photos as $photo)
            <img src="{{ asset('storage/'.$photo->foto) }}" class="img-thumbnail me-2" style="width:150px">
        @endforeach
    </div>
    @auth
        <a href="{{ route('pets.edit', $pet) }}" class="btn btn-primary">Editar</a>
        <form action="{{ route('pets.destroy', $pet) }}" method="POST" class="d-inline">
            @csrf @method('DELETE')
            <button class="btn btn-danger">Excluir</button>
        </form>
    @endauth
    <a href="{{ route('pets.meus') }}" class="btn btn-light">Voltar</a>
@endsection
