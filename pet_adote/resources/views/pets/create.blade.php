@extends('layouts.app')

@section('content')
    <h1>{{ isset($pet) ? 'Editar Pet' : 'Novo Pet' }}</h1>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form
        method="POST"
        action="{{ isset($pet) ? route('pets.update', $pet) : route('pets.store') }}"
        enctype="multipart/form-data">
        @csrf
        @if(isset($pet)) @method('PUT') @endif

        <div class="mb-3">
            <label>Nome</label>
            <input type="text" name="nome" class="form-control" value="{{ old('nome', $pet->nome ?? '') }}" required>
        </div>

        <div class="mb-3">
            <label>Idade</label>
            <input type="number" name="idade" class="form-control" value="{{ old('idade', $pet->idade ?? '') }}" required>
        </div>

        <!-- País -->
        <div class="mb-3">
            <label for="pais">País</label>
            <select id="pais" name="pais" class="form-control" required>
                <option value="Brasil" selected>Brasil</option>
            </select>
        </div>

        <div class="mb-3">
        <label class="form-label">Estado *</label>
            <input 
                id="estado" 
                name="estado" 
                list="lista-estados" 
                class="form-control" 
                value="{{ old('estado') }}" 
                required
            >
        <datalist id="lista-estados"></datalist>
        </div>

        <div class="mb-3">
        <label class="form-label">Cidade *</label>
            <input 
                id="cidade" 
                name="cidade" 
                list="lista-cidades" 
                class="form-control" 
                value="{{ old('cidade') }}" 
                required
            >
        <datalist id="lista-cidades"></datalist>
        </div>

        <div class="mb-3">
            <label>Porte</label>
            <select name="porte" class="form-control" required>
                <option value="">Selecione</option>
                @foreach(['pequeno', 'medio', 'grande'] as $porte)
                    <option value="{{ $porte }}" {{ old('porte', $pet->porte ?? '') == $porte ? 'selected' : '' }}>
                        {{ ucfirst($porte) }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Raça</label>
            <input type="text" name="raca" class="form-control" value="{{ old('raca', $pet->raca ?? '') }}" required>
        </div>

        <div class="mb-3">
            <label>Tipo</label>
            <input type="text" name="tipo" class="form-control" value="{{ old('tipo', $pet->tipo ?? '') }}" required>
        </div>

        <div class="mb-3">
            <label>Descrição</label>
            <textarea name="descricao" class="form-control">{{ old('descricao', $pet->descricao ?? '') }}</textarea>
        </div>

        <div class="mb-3">
            <label>Status</label>
            <select name="status" class="form-control" required>
                <option value="">Selecione</option>
                @foreach(['disponivel', 'indisponivel', 'adotado'] as $status)
                    <option value="{{ $status }}" {{ old('status', $pet->status ?? '') == $status ? 'selected' : '' }}>
                        {{ ucfirst($status) }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Fotos (várias):</label>
            <input type="file" name="photos[]" multiple class="form-control">
        </div>

        <button class="btn btn-success">Salvar</button>
    </form>
    <script src="{{ asset('js/location.js') }}"></script>
@endsection
