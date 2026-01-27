@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm border-0 rounded-3">
            <div class="card-header bg-primary text-white text-center py-3">
                <h4 class="mb-0 fw-bold">Editar Meu Perfil</h4>
            </div>
            <div class="card-body p-4">

                <form method="POST" action="{{ route('perfil.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    {{-- Seção de Foto de Perfil --}}
                    <h5 class="text-secondary mb-3 border-bottom pb-2">Foto de Perfil</h5>
                    <div class="mb-4 text-center">
                        @if($user->profile_photo)
                            <img src="{{ asset('storage/' . $user->profile_photo) }}" 
                                 class="rounded-circle mb-3 border shadow-sm" 
                                 width="120" height="120" style="object-fit: cover;">
                        @else
                            <div class="rounded-circle bg-secondary text-white d-inline-flex align-items-center justify-content-center mb-3 shadow-sm" 
                                 style="width: 120px; height: 120px; font-size: 3rem;">
                                {{ substr($user->name, 0, 1) }}
                            </div>
                        @endif
                        <input type="file" name="profile_photo" class="form-control w-50 mx-auto @error('profile_photo') is-invalid @enderror">
                        @error('profile_photo')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Seção de Dados Pessoais --}}
                    <h5 class="text-secondary mb-3 border-bottom pb-2">Dados Pessoais</h5>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Nome Completo <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                                   required value="{{ old('name', $user->name) }}">
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                                   required value="{{ old('email', $user->email) }}">
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">CPF <span class="text-danger">*</span></label>
                            <input type="text" name="cpf" class="form-control @error('cpf') is-invalid @enderror" 
                                   oninput="mascaraCPF(this)" maxlength="14" placeholder="000.000.000-00" 
                                   value="{{ old('cpf', $user->cpf) }}" required>
                            @error('cpf') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Whatsapp / Contato <span class="text-danger">*</span></label>
                            <input type="text" name="contato" class="form-control @error('contato') is-invalid @enderror" 
                                   oninput="mascaraTelefone(this)" maxlength="14" placeholder="(00)000000000" 
                                   value="{{ old('contato', $user->contato) }}" required>
                            @error('contato') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    {{-- Seção de Redes Sociais --}}
                    <h5 class="text-secondary mb-3 border-bottom pb-2 mt-4">Redes Sociais <small class="text-muted fw-normal fs-6">(Opcional)</small></h5>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label"><i class="bi bi-facebook text-primary"></i> Facebook (Link)</label>
                            <input type="url" name="facebook" class="form-control @error('facebook') is-invalid @enderror" 
                                   value="{{ old('facebook', $user->facebook) }}" placeholder="https://facebook.com/seu.perfil">
                            @error('facebook') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label"><i class="bi bi-instagram text-danger"></i> Instagram (Link)</label>
                            <input type="url" name="instagram" class="form-control @error('instagram') is-invalid @enderror" 
                                   value="{{ old('instagram', $user->instagram) }}" placeholder="https://instagram.com/seu.perfil">
                            @error('instagram') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    {{-- Seção de Endereço --}}
                    <h5 class="text-secondary mb-3 border-bottom pb-2 mt-4">Endereço</h5>
                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="form-label">CEP</label>
                            <input type="text" id="cep" class="form-control" onblur="buscarCep(this.value)" 
                                   placeholder="00000-000">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">País <span class="text-danger">*</span></label>
                            <input type="text" name="pais" id="pais" class="form-control @error('pais') is-invalid @enderror" 
                                   required value="{{ old('pais', $user->pais) }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Estado <span class="text-danger">*</span></label>
                            <input type="text" name="estado" id="estado" class="form-control @error('estado') is-invalid @enderror" 
                                   required value="{{ old('estado', $user->estado) }}">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Cidade <span class="text-danger">*</span></label>
                            <input type="text" name="cidade" id="cidade" class="form-control @error('cidade') is-invalid @enderror" 
                                   required value="{{ old('cidade', $user->cidade) }}">
                        </div>
                    </div>

                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-success btn-lg fw-bold">Salvar Alterações</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

{{-- Scripts --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="{{ asset('js/masks.js') }}"></script>
<script src="{{ asset('js/location.js') }}"></script>

<script>
    // Executa as máscaras ao carregar a página para os dados que já vêm do banco
    document.addEventListener('DOMContentLoaded', function() {
        const cpfInput = document.querySelector('input[name="cpf"]');
        const telInput = document.querySelector('input[name="contato"]');
        if(cpfInput) mascaraCPF(cpfInput);
        if(telInput) mascaraTelefone(telInput);
    });
</script>
@endsection