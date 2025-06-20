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
            <label for="raca" class="form-label">Raça</label>
            <input type="text" name="raca" id="raca" class="form-control @error('raca') is-invalid @enderror" value="{{ old('raca', $pet->raca) }}" required>
            @error('raca')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="tipo" class="form-label">Tipo</label>
            <input type="text" name="tipo" id="tipo" class="form-control @error('tipo') is-invalid @enderror" value="{{ old('tipo', $pet->tipo) }}" required>
            @error('tipo')
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
            <label for="pais" class="form-label">País</label>
            <input type="text" name="pais" id="pais" class="form-control @error('pais') is-invalid @enderror" value="{{ old('pais', $pet->pais) }}" required>
            @error('pais')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="estado" class="form-label">Estado</label>
            <input type="text" name="estado" id="estado" class="form-control @error('estado') is-invalid @enderror" value="{{ old('estado', $pet->estado) }}" required>
            @error('estado')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="cidade" class="form-label">Cidade</label>
            <input type="text" name="cidade" id="cidade" class="form-control @error('cidade') is-invalid @enderror" value="{{ old('cidade', $pet->cidade) }}" required>
            @error('cidade')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="photos" class="form-label">Fotos (opcional, pode enviar várias)</label>
            <input type="file" name="photos[]" id="photos" class="form-control" multiple accept="image/*">
        </div>

        <button type="submit" class="btn btn-primary">Salvar Alterações</button>
        <a href="{{ route('pets.meus') }}" class="btn btn-secondary">Cancelar</a>
    </form>
    <script src="{{ asset('js/location.js') }}"></script>
@endsection
