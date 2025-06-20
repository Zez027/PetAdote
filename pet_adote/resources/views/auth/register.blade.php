@extends('layouts.app')

@section('content')
<h1>Cadastro</h1>

<form method="POST" action="{{ route('register') }}">
    @csrf

    @foreach(['name','email','cpf','contato'] as $f)
        <div class="mb-3">
            <label class="form-label">
                {{ ucfirst($f) }} <span class="text-danger">*</span>
            </label>
            <input 
                type="{{ $f == 'email' ? 'email' : 'text' }}"
                name="{{ $f }}"
                id="{{ $f == 'cpf' ? 'cpf' : ($f == 'contato' ? 'contato' : '') }}"
                class="form-control @error($f) is-invalid @enderror"
                value="{{ old($f) }}"
                required
            >
            @error($f)
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    @endforeach

    <div class="mb-3">
        <label class="form-label">País *</label>
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
        <label class="form-label">Facebook</label>
        <input type="url" name="facebook" class="form-control @error('facebook') is-invalid @enderror" value="{{ old('facebook') }}">
        @error('facebook')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="mb-3">
        <label class="form-label">Instagram</label>
        <input type="url" name="instagram" class="form-control @error('instagram') is-invalid @enderror" value="{{ old('instagram') }}">
        @error('instagram')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="mb-3">
        <label class="form-label">Senha <span class="text-danger">*</span></label>
        <div class="input-group">
            <input type="password" name="password" id="senha" class="form-control @error('password') is-invalid @enderror" required>
            <span class="input-group-text" onclick="togglePassword('senha')" style="cursor: pointer;">👁️</span>
        </div>
        @error('password')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label class="form-label">Confirmar Senha <span class="text-danger">*</span></label>
        <div class="input-group">
            <input type="password" name="password_confirmation" id="confirmar_senha" class="form-control" required>
            <span class="input-group-text" onclick="togglePassword('confirmar_senha')" style="cursor: pointer;">👁️</span>
        </div>
    </div>

    <button class="btn btn-primary">Registrar</button>
</form>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
<script>
    $(document).ready(function(){
        $('#cpf').mask('000.000.000-00');
        $('#contato').mask('(00) 00000-0000');
    });

    function togglePassword(id) {
        const input = document.getElementById(id);
        input.type = input.type === 'password' ? 'text' : 'password';
    }
</script>
<script src="{{ asset('js/location.js') }}"></script>
@endsection