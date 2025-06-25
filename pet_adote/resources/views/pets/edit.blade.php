@extends('layouts.app')

@section('content')
    <h1 class="mb-4">Editar Pet: {{ $pet->nome }}</h1>

    <form action="{{ route('pets.update', $pet) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="nome" class="form-label">Nome</label>
            <input type="text" name="nome" id="nome" class="form-control @error('nome') is-invalid @enderror" value="{{ old('nome', $pet->nome) }}" required>
            @error('nome')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="idade" class="form-label">Idade</label>
            <input type="number" name="idade" id="idade" class="form-control @error('idade') is-invalid @enderror" value="{{ old('idade', $pet->idade) }}" required min="0">
            @error('idade')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="porte" class="form-label">Porte</label>
            <select name="porte" id="porte" class="form-select @error('porte') is-invalid @enderror" required>
                <option value="pequeno" {{ old('porte', $pet->porte) == 'pequeno' ? 'selected' : '' }}>Pequeno</option>
                <option value="medio" {{ old('porte', $pet->porte) == 'medio' ? 'selected' : '' }}>Médio</option>
                <option value="grande" {{ old('porte', $pet->porte) == 'grande' ? 'selected' : '' }}>Grande</option>
            </select>
            @error('porte')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="tipo" class="form-label">Tipo</label>
            <input type="text" name="tipo" id="tipo" class="form-control @error('tipo') is-invalid @enderror" value="{{ old('tipo', $pet->tipo) }}" required list="lista-tipos">
            <datalist id="lista-tipos">
                <option value="Cachorro">
                <option value="Gato">
                <option value="Pássaro">
                <option value="Coelho">
                <option value="Hamster">
                <option value="Réptil">
                <option value="Outro">
            </datalist>
            @error('tipo')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="raca" class="form-label">Raça</label>
            <input type="text" name="raca" id="raca" class="form-control @error('raca') is-invalid @enderror" value="{{ old('raca', $pet->raca) }}" required list="lista-racas">
            <datalist id="lista-racas"></datalist>
            @error('raca')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="descricao" class="form-label">Descrição</label>
            <textarea name="descricao" id="descricao" class="form-control @error('descricao') is-invalid @enderror" rows="3">{{ old('descricao', $pet->descricao) }}</textarea>
            @error('descricao')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                <option value="disponivel" {{ old('status', $pet->status) == 'disponivel' ? 'selected' : '' }}>Disponível</option>
                <option value="indisponivel" {{ old('status', $pet->status) == 'indisponivel' ? 'selected' : '' }}>Indisponível</option>
                <option value="adotado" {{ old('status', $pet->status) == 'adotado' ? 'selected' : '' }}>Adotado</option>
            </select>
            @error('status')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
        <label class="form-label">País *</label>
        <input 
            id="pais" 
            name="pais" 
            list="lista-paises" 
            class="form-control" 
            value="{{ old('pais', $pet->pais ?? 'Brasil') }}" 
            required
        >
        <datalist id="lista-paises">
            <option value="Brasil">
        </datalist>
    </div>

    <div class="mb-3">
        <label class="form-label">Estado *</label>
        <input 
            id="estado" 
            name="estado" 
            list="lista-estados" 
            class="form-control" 
            value="{{ old('estado', $pet->estado ?? '') }}" 
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
            value="{{ old('cidade', $pet->cidade ?? '') }}" 
            required
            autocomplete="off"
        >
        <datalist id="lista-cidades"></datalist>
    </div>

        <div class="mb-3">
            <label for="photos" class="form-label">Fotos (opcional, pode enviar várias)</label>
            <input type="file" name="photos[]" id="photos" class="form-control" multiple accept="image/*">
        </div>

        <button type="submit" class="btn btn-primary">Salvar Alterações</button>
        <a href="{{ route('pets.meus') }}" class="btn btn-secondary">Cancelar</a>
    </form>
    <script src="{{ asset('js/location.js') }}"></script>
    <script src="{{ asset('js/animal_breeds.js') }}"></script>
@endsection
