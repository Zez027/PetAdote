@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">

        <div class="card card-custom">
            <div class="card-header-custom text-center">
                <strong>Cadastro</strong>
            </div>

            <div class="card-body">

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    @foreach(['name','email','cpf','contato'] as $f)
                        <div class="mb-3">
                            <label class="form-label">
                                {{ ucfirst($f) }} <span class="text-danger">*</span>
                            </label>
                            <input 
                                type="{{ $f === 'email' ? 'email' : 'text' }}"
                                name="{{ $f }}"
                                id="{{ $f }}"
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
                        <input 
                            id="pais" 
                            name="pais" 
                            list="lista-paises" 
                            class="form-control @error('pais') is-invalid @enderror"
                            value="{{ old('pais', 'Brasil') }}" 
                            required
                        >
                        <datalist id="lista-paises">
                            <option value="Brasil">
                        </datalist>
                        @error('pais')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Estado *</label>
                        <input 
                            id="estado" 
                            name="estado" 
                            list="lista-estados" 
                            class="form-control @error('estado') is-invalid @enderror"
                            value="{{ old('estado') }}" 
                            required
                        >
                        <datalist id="lista-estados"></datalist>
                        @error('estado')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Cidade *</label>
                        <input 
                            id="cidade" 
                            name="cidade" 
                            list="lista-cidades" 
                            class="form-control @error('cidade') is-invalid @enderror"
                            value="{{ old('cidade') }}" 
                            required
                            autocomplete="off"
                        >
                        <datalist id="lista-cidades"></datalist>
                        @error('cidade')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Facebook</label>
                        <input 
                            type="url" 
                            name="facebook" 
                            class="form-control @error('facebook') is-invalid @enderror" 
                            value="{{ old('facebook') }}"
                        >
                        @error('facebook')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Instagram</label>
                        <input 
                            type="url" 
                            name="instagram" 
                            class="form-control @error('instagram') is-invalid @enderror" 
                            value="{{ old('instagram') }}"
                        >
                        @error('instagram')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Senha *</label>
                        <div class="input-group">
                            <input 
                                type="password" 
                                name="password" 
                                id="senha" 
                                class="form-control @error('password') is-invalid @enderror" 
                                required
                            >
                            <span class="input-group-text" onclick="togglePassword('senha')" style="cursor:pointer;">👁️</span>
                        </div>
                        @error('password')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Confirmar Senha *</label>
                        <div class="input-group">
                            <input 
                                type="password" 
                                name="password_confirmation" 
                                id="confirmar_senha" 
                                class="form-control" 
                                required
                            >
                            <span class="input-group-text" onclick="togglePassword('confirmar_senha')" style="cursor:pointer;">👁️</span>
                        </div>
                    </div>

                    <button class="btn btn-green w-100">Registrar</button>

                </form>

            </div>
        </div>

    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
<script src="{{ asset('js/masks.js') }}"></script>
<script src="{{ asset('js/location.js') }}"></script>

@endsection
