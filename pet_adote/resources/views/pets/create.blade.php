@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Cadastrar Pet</h2>

    <form action="{{ route('pets.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row">

            {{-- Nome --}}
            <div class="col-md-6 mb-3">
                <label class="form-label">Nome</label>
                <input type="text" name="nome" class="form-control" value="{{ old('nome') }}" required>
            </div>

            {{-- Idade --}}
            <div class="col-md-3 mb-3">
                <label class="form-label">Idade</label>
                <input type="number" name="idade" class="form-control" value="{{ old('idade') }}" required>
            </div>

            {{-- Gênero --}}
            <div class="col-md-3 mb-3">
                <label class="form-label">Gênero</label>
                <select name="genero" class="form-control" required>
                    <option value="">Selecione...</option>
                    <option value="Macho" {{ old('genero')=='Macho' ? 'selected' : '' }}>Macho</option>
                    <option value="Fêmea" {{ old('genero')=='Fêmea' ? 'selected' : '' }}>Fêmea</option>
                </select>
            </div>

            {{-- Porte --}}
            <div class="col-md-4 mb-3">
                <label class="form-label">Porte</label>
                <select name="porte" class="form-control" required>
                    <option value="">Selecione...</option>
                    <option value="Pequeno" {{ old('porte')=='Pequeno' ? 'selected' : '' }}>Pequeno</option>
                    <option value="Médio" {{ old('porte')=='Médio' ? 'selected' : '' }}>Médio</option>
                    <option value="Grande" {{ old('porte')=='Grande' ? 'selected' : '' }}>Grande</option>
                </select>
            </div>

            {{-- Tipo --}}
            <div class="col-md-4 mb-3">
                <label class="form-label">Tipo</label>
                <select name="tipo" class="form-control" required>
                    <option value="">Selecione...</option>
                    @foreach($types as $type)
                    <option value="{{ $type }}" {{ old('tipo') == $type ? 'selected' : '' }}>
                        {{ $type }}
                    </option>
                    @endforeach
                </select>
            </div>

            {{-- Raça --}}
            <div class="col-md-4 mb-3">
                <label class="form-label">Raça</label>
                <input type="text" name="raca" class="form-control" value="{{ old('raca') }}" required>
            </div>

            <!-- LOCALIZAÇÃO -->
            <div class="col-md-4 mb-3">
                <label class="form-label">País</label>
                <input type="text" name="pais" class="form-control" value="Brasil" readonly>
            </div>

            <div class="col-md-4 mb-3">
                <label class="form-label">Estado</label>
                <select name="estado" id="estado" class="form-control" data-value="{{ old('estado') }}" required></select>
            </div>

            <div class="col-md-4 mb-3">
                <label class="form-label">Cidade</label>
                <select name="cidade" id="cidade" class="form-control" data-value="{{ old('cidade') }}" required></select>
            </div>

            {{-- Descrição --}}
            <div class="col-md-12 mb-3">
                <label class="form-label">Descrição</label>
                <textarea name="descricao" class="form-control" rows="3">{{ old('descricao') }}</textarea>
            </div>

            <div class="row mb-3">
                <div class="col-md-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="vacinado" id="vacinado">
                        <label class="form-check-label" for="vacinado">Vacinado</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="castrado" id="castrado">
                        <label class="form-check-label" for="castrado">Castrado</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="vermifugado" id="vermifugado">
                        <label class="form-check-label" for="vermifugado">Vermifugado</label>
                    </div>
                </div>
            </div>

             <!-- Status -->
             <div class="col-md-3">
                <select name="status" class="form-select" required>
                    <option value="">Status</option>
                    <option value="disponivel" {{ old('status') == 'disponivel' ? 'selected' : '' }}>Disponível</option>
                    <option value="indisponivel" {{ old('status') == 'indisponivel' ? 'selected' : '' }}>Indisponível</option>
                    <option value="adotado" {{ old('status') == 'adotado' ? 'selected' : '' }}>Adotado</option>
                </select>
            </div>

            {{-- Fotos --}}
            <div class="col-md-12 mb-3">
                <label class="form-label">Fotos</label>
                <input type="file" name="photos[]" class="form-control" multiple>
            </div>

        </div>

        <button class="btn btn-success w-100">Salvar</button>

    </form>
</div>

<script src="/js/location.js"></script>
@endsection
