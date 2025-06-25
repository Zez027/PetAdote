@extends('layouts.app')

@section('content')
<h1>Editar Perfil</h1>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<form method="POST" action="{{ route('perfil.update') }}">
    @csrf
    @method('PUT')

    @foreach(['name','email','cpf','contato'] as $f)
        <div class="mb-3">
            <label class="form-label">{{ ucfirst($f) }} <span class="text-danger">*</span></label>
            <input 
                type="{{ $f == 'email' ? 'email' : 'text' }}"
                name="{{ $f }}"
                id="{{ $f }}"
                class="form-control @error($f) is-invalid @enderror"
                value="{{ old($f, $user->$f) }}"
                required
            >
            @error($f)
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    @endforeach

    <div class="mb-3">
        <label class="form-label">País *</label>
        <input 
            id="pais" 
            name="pais" 
            list="lista-paises" 
            class="form-control" 
            value="{{ old('pais', $user->pais ?? 'Brasil') }}" 
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
            value="{{ old('estado', $user->estado ?? '') }}" 
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
            value="{{ old('cidade', $user->cidade ?? '') }}" 
            required
            autocomplete="off"
        >
        <datalist id="lista-cidades"></datalist>
    </div>

    <div class="mb-3">
        <label>Facebook</label>
        <input type="url" name="facebook" class="form-control" value="{{ old('facebook', $user->facebook) }}">
    </div>

    <div class="mb-3">
        <label>Instagram</label>
        <input type="url" name="instagram" class="form-control" value="{{ old('instagram', $user->instagram) }}">
    </div>

    <button class="btn btn-primary">Salvar Alterações</button>
</form>

<!-- Bibliotecas e scripts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
<script src="{{ asset('js/masks.js') }}"></script>
<script src="{{ asset('js/location.js') }}"></script>
@endsection
