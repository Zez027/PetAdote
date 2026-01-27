@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">

        <div class="card shadow-lg border-0 mt-4">
            <div class="card-header bg-primary text-white text-center">
                <h4>Editar Perfil</h4>
            </div>

            <div class="card-body">

                @if(session('success'))
                    <div class="alert alert-success text-center">{{ session('success') }}</div>
                @endif

                <form method="POST" action="{{ route('perfil.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    {{-- CAMPO DE FOTO --}}
                    <div class="mb-4 text-center">
                        <label class="form-label d-block fw-bold">Foto de Perfil</label>
                        
                        @if($user->profile_photo)
                            <img src="{{ asset('storage/' . $user->profile_photo) }}" 
                                class="rounded-circle mb-3 border shadow-sm" 
                                width="100" height="100" style="object-fit: cover;">
                        @else
                            <div class="rounded-circle bg-secondary text-white d-inline-flex align-items-center justify-content-center mb-3 shadow-sm" 
                                style="width: 100px; height: 100px; font-size: 2rem;">
                                {{ substr($user->name, 0, 1) }}
                            </div>
                        @endif

                        <input type="file" name="profile_photo" class="form-control">
                    </div>

                    @foreach(['name','email','cpf','contato'] as $f)
                        <div class="mb-3">
                            <label class="form-label">{{ ucfirst($f) }} <span class="text-danger">*</span></label>
                            <input 
                                type="{{ $f == 'email' ? 'email' : 'text' }}"
                                name="{{ $f }}"
                                class="form-control @error($f) is-invalid @enderror"
                                value="{{ old($f, $user->$f) }}"
                                required
                            >
                            @error($f)
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    @endforeach

                    <hr>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">País *</label>
                            <input id="pais" name="pais" class="form-control"
                                list="lista-paises"
                                value="{{ old('pais', $user->pais ?? 'Brasil') }}"
                                required>
                            <datalist id="lista-paises">
                                <option value="Brasil">
                            </datalist>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Estado *</label>
                            <input id="estado" name="estado" list="lista-estados"
                                class="form-control"
                                value="{{ old('estado', $user->estado ?? '') }}"
                                required>
                            <datalist id="lista-estados"></datalist>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Cidade *</label>
                            <input id="cidade" name="cidade" list="lista-cidades"
                                class="form-control"
                                value="{{ old('cidade', $user->cidade ?? '') }}"
                                required>
                            <datalist id="lista-cidades"></datalist>
                        </div>
                    </div>

                    <hr>

                    <div class="mb-3">
                        <label>Facebook</label>
                        <input type="url" name="facebook" class="form-control"
                            value="{{ old('facebook', $user->facebook) }}">
                    </div>

                    <div class="mb-3">
                        <label>Instagram</label>
                        <input type="url" name="instagram" class="form-control"
                            value="{{ old('instagram', $user->instagram) }}">
                    </div>

                    <button class="btn btn-primary w-100 mt-3">Salvar Alterações</button>

                </form>

            </div>
        </div>

    </div>
</div>

<script src="{{ asset('js/location.js') }}"></script>
@endsection
