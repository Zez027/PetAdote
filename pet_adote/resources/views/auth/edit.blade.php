@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            {{-- Cartão Principal: Edição de Perfil --}}
            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
                <div class="d-flex align-items-center mb-4">
                    <i class="bi bi-person-gear text-primary me-2" style="font-size: 2rem;"></i>
                    <h3 class="fw-bold mb-0">Editar Perfil</h3>
                </div>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form method="POST" action="{{ route('perfil.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    {{-- Foto de Perfil --}}
                    <div class="row mb-4 align-items-center justify-content-center text-center">
                        <div class="col-md-12">
                            <div class="position-relative d-inline-block">
                                @if($user->profile_photo)
                                    <img src="{{ asset('storage/' . $user->profile_photo) }}" 
                                         class="rounded-circle shadow-sm border border-3 border-white" 
                                         width="120" height="120" style="object-fit: cover;" id="preview-foto">
                                @else
                                    <div class="rounded-circle bg-light d-flex align-items-center justify-content-center shadow-sm border border-3 border-white" 
                                         style="width: 120px; height: 120px;" id="preview-placeholder">
                                        <i class="bi bi-person text-secondary" style="font-size: 3rem;"></i>
                                    </div>
                                @endif
                                
                                <label for="profile_photo" class="position-absolute bottom-0 end-0 btn btn-primary btn-sm rounded-circle shadow-sm" style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center; cursor: pointer;">
                                    <i class="bi bi-camera-fill"></i>
                                </label>
                                <input type="file" name="profile_photo" id="profile_photo" class="d-none" accept="image/*" onchange="previewImage(this)">
                            </div>
                            <p class="text-muted small mt-2">Clique no ícone da câmera para alterar sua foto.</p>
                        </div>
                    </div>

                    {{-- Dados Pessoais --}}
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-uppercase text-muted">Nome Completo</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-person text-muted"></i></span>
                                <input type="text" name="name" class="form-control border-start-0 ps-0 @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
                            </div>
                            @error('name')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-uppercase text-muted">E-mail</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-envelope text-muted"></i></span>
                                <input type="email" name="email" class="form-control border-start-0 ps-0 @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
                            </div>
                            @error('email')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small text-muted fw-bold">CPF *</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-card-heading text-muted"></i></span>
                                <input type="text" name="cpf" class="form-control border-start-0 ps-0 cpf-mask @error('cpf') is-invalid @enderror" 
                                       oninput="mascaraCPF(this)" maxlength="14" placeholder="000.000.000-00" value="{{ old('cpf', auth()->user()->cpf) }}" required>
                            </div>
                            @error('cpf')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small text-muted fw-bold">WHATSAPP / CELULAR *</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-whatsapp text-muted"></i></span>
                                <input type="text" name="contato" class="form-control border-start-0 ps-0 phone-mask @error('contato') is-invalid @enderror" 
                                       oninput="mascaraTelefone(this)" maxlength="14" placeholder="(00) 00000-0000" value="{{ old('contato', auth()->user()->contato) }}" required>
                            </div>
                            @error('contato')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>

                        {{-- Endereço --}}
                        <div class="col-12 mt-4">
                            <h6 class="fw-bold border-bottom pb-2 mb-3 text-secondary">Localização</h6>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label small text-muted">País</label>
                            <select id="pais" name="pais" class="form-select bg-light" required>
                                <option value="Brasil" {{ old('pais', $user->pais) == 'Brasil' ? 'selected' : '' }}>Brasil</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label small text-muted">Estado</label>
                            <select id="estado" name="estado" class="form-select bg-light" required data-selected="{{ old('estado', $user->estado) }}">
                                <option value="">Selecione...</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label small text-muted">Cidade</label>
                            <select id="cidade" name="cidade" class="form-select bg-light" required data-selected="{{ old('cidade', $user->cidade) }}">
                                <option value="">Selecione...</option>
                            </select>
                        </div>

                        {{-- Redes Sociais --}}
                        <div class="col-12 mt-4">
                            <h6 class="fw-bold border-bottom pb-2 mb-3 text-secondary">Redes Sociais (Opcional)</h6>
                        </div>

                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-text bg-white text-primary border-end-0"><i class="bi bi-facebook"></i></span>
                                <input type="url" name="facebook" class="form-control border-start-0 ps-0" placeholder="Link do Facebook" value="{{ old('facebook', $user->facebook) }}">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-text bg-white text-danger border-end-0"><i class="bi bi-instagram"></i></span>
                                <input type="url" name="instagram" class="form-control border-start-0 ps-0" placeholder="Link do Instagram" value="{{ old('instagram', $user->instagram) }}">
                            </div>
                        </div>
                    </div>

                    <div class="d-grid gap-2 mt-5">
                        <button type="submit" class="btn btn-primary rounded-pill py-2 fw-bold shadow-sm hover-scale">
                            Salvar Alterações
                        </button>
                    </div>
                </form>

                {{-- NOVA SEÇÃO: Segurança da Conta --}}
                <div class="mt-5 pt-4 border-top">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                        <div>
                            <h5 class="fw-bold text-dark mb-1">
                                <i class="bi bi-shield-lock text-success me-2"></i>Segurança da Conta
                            </h5>
                            <p class="text-muted small mb-0">Mantenha sua conta segura alterando sua senha periodicamente.</p>
                        </div>
                        <a href="{{ route('perfil.password.edit') }}" class="btn btn-outline-secondary rounded-pill px-4">
                            Alterar Senha
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

{{-- Scripts para Máscaras e Preview de Imagem --}}
<script>
    function previewImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                var img = document.getElementById('preview-foto');
                if(img) {
                    img.src = e.target.result;
                } else {
                    // Se não tiver imagem (estiver mostrando o placeholder), recarrega a página ou manipula o DOM para trocar a div por img.
                    // Simplificação: apenas exibe se já existir a tag img, caso contrário o usuário verá após salvar.
                    // Melhoria: substituir a div placeholder pela img dinamicamente.
                    let placeholder = document.getElementById('preview-placeholder');
                    if(placeholder) {
                        placeholder.outerHTML = `<img src="${e.target.result}" class="rounded-circle shadow-sm border border-3 border-white" width="120" height="120" style="object-fit: cover;" id="preview-foto">`;
                    }
                }
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>

{{-- Importando seus scripts de localização e máscaras --}}
<script src="{{ asset('js/location.js') }}"></script>
<script src="{{ asset('js/masks.js') }}"></script>
@endsection