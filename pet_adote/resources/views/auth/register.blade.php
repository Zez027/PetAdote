@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            
            {{-- Cartão Principal: Registro --}}
            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
                
                {{-- Cabeçalho --}}
                <div class="d-flex align-items-center mb-4 border-bottom pb-3">
                    <i class="bi bi-person-plus text-primary me-2" style="font-size: 2rem;"></i>
                    <h3 class="fw-bold mb-0">Criar Conta</h3>
                </div>

                <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
                    @csrf

                    {{-- Foto de Perfil --}}
                    <div class="row mb-4 align-items-center justify-content-center text-center">
                        <div class="col-md-12">
                            <label class="form-label fw-bold small text-muted d-block mb-2">FOTO DE PERFIL (OPCIONAL)</label>
                            
                            <div class="position-relative d-inline-block">
                                <div class="rounded-circle bg-light d-flex align-items-center justify-content-center shadow-sm border border-3 border-white" 
                                     style="width: 120px; height: 120px;" id="preview-placeholder">
                                    <i class="bi bi-person text-secondary" style="font-size: 3rem;"></i>
                                </div>
                                <img id="preview-foto" src="#" class="rounded-circle shadow-sm border border-3 border-white d-none" 
                                     width="120" height="120" style="object-fit: cover;">
                                
                                <label for="profile_photo" class="position-absolute bottom-0 end-0 btn btn-primary btn-sm rounded-circle shadow-sm hover-scale" 
                                       style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center; cursor: pointer;">
                                    <i class="bi bi-camera-fill"></i>
                                </label>
                                <input type="file" name="profile_photo" id="profile_photo" class="d-none" accept="image/*" onchange="previewImage(this)">
                            </div>
                        </div>
                    </div>

                    {{-- Dados Pessoais --}}
                    <h6 class="fw-bold text-secondary mb-3 mt-2"><i class="bi bi-person-vcard me-1"></i> Dados Pessoais</h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small text-muted fw-bold">NOME COMPLETO *</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-person text-muted"></i></span>
                                <input type="text" name="name" class="form-control border-start-0 ps-0 @error('name') is-invalid @enderror" required value="{{ old('name') }}">
                            </div>
                            @error('name')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small text-muted fw-bold">E-MAIL *</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-envelope text-muted"></i></span>
                                <input type="email" name="email" class="form-control border-start-0 ps-0 @error('email') is-invalid @enderror" required value="{{ old('email') }}">
                            </div>
                            @error('email')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small text-muted fw-bold">CPF *</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-card-heading text-muted"></i></span>
                                {{-- REMOVIDO oninput, mantido apenas a classe cpf-mask para o jQuery --}}
                                <input type="text" name="cpf" class="form-control border-start-0 ps-0 cpf-mask @error('cpf') is-invalid @enderror" 
                                       placeholder="000.000.000-00" value="{{ old('cpf') }}" required>
                            </div>
                            @error('cpf')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small text-muted fw-bold">WHATSAPP / CELULAR *</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-whatsapp text-muted"></i></span>
                                {{-- REMOVIDO oninput, mantido apenas a classe phone-mask para o jQuery --}}
                                <input type="text" name="contato" class="form-control border-start-0 ps-0 phone-mask @error('contato') is-invalid @enderror" 
                                       placeholder="(00) 00000-0000" value="{{ old('contato') }}" required>
                            </div>
                            @error('contato')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    {{-- Localização --}}
                    <h6 class="fw-bold text-secondary mb-3 mt-4 pt-3 border-top"><i class="bi bi-geo-alt me-1"></i> Localização</h6>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label small text-muted fw-bold">PAÍS *</label>
                            <select id="pais" name="pais" class="form-select bg-light" required>
                                <option value="Brasil" selected>Brasil</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small text-muted fw-bold">ESTADO *</label>
                            <select id="estado" name="estado" class="form-select bg-light @error('estado') is-invalid @enderror" 
                                    required data-selected="{{ old('estado') }}">
                                <option value="">Carregando...</option>
                            </select>
                            @error('estado')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small text-muted fw-bold">CIDADE *</label>
                            <select id="cidade" name="cidade" class="form-select bg-light @error('cidade') is-invalid @enderror" 
                                    required disabled data-selected="{{ old('cidade') }}">
                                <option value="">Selecione o estado...</option>
                            </select>
                            @error('cidade')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    {{-- Redes Sociais --}}
                    <h6 class="fw-bold text-secondary mb-3 mt-4 pt-3 border-top"><i class="bi bi-share me-1"></i> Redes Sociais (Opcional)</h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-text bg-white text-primary border-end-0"><i class="bi bi-facebook"></i></span>
                                <input type="url" name="facebook" class="form-control border-start-0 ps-0" placeholder="Link do Facebook" value="{{ old('facebook') }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-text bg-white text-danger border-end-0"><i class="bi bi-instagram"></i></span>
                                <input type="url" name="instagram" class="form-control border-start-0 ps-0" placeholder="Link do Instagram" value="{{ old('instagram') }}">
                            </div>
                        </div>
                    </div>

                    {{-- Segurança --}}
                    <h6 class="fw-bold text-secondary mb-3 mt-4 pt-3 border-top"><i class="bi bi-shield-lock me-1"></i> Segurança</h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small text-muted fw-bold">SENHA *</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-key text-muted"></i></span>
                                <input type="password" name="password" id="senha" class="form-control border-start-0 border-end-0 ps-0 @error('password') is-invalid @enderror" required>
                                <span class="input-group-text bg-light border-start-0" onclick="togglePassword('senha')" style="cursor:pointer;">
                                    <i class="bi bi-eye text-primary"></i>
                                </span>
                            </div>
                            @error('password')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            <div class="form-text small">Mínimo 8 caracteres (letras, números e símbolos).</div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small text-muted fw-bold">CONFIRMAR SENHA *</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-key text-muted"></i></span>
                                <input type="password" name="password_confirmation" id="confirmar_senha" class="form-control border-start-0 border-end-0 ps-0" required>
                                <span class="input-group-text bg-light border-start-0" onclick="togglePassword('confirmar_senha')" style="cursor:pointer;">
                                    <i class="bi bi-eye text-primary"></i>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="d-grid gap-2 mt-5">
                        <button type="submit" class="btn btn-primary rounded-pill py-3 fw-bold shadow-sm hover-scale text-uppercase">
                            Criar Conta
                        </button>
                    </div>
                </form>

            </div>

            <div class="text-center mt-4">
                <p class="text-muted">Já tem uma conta? <a href="{{ route('login') }}" class="text-decoration-none fw-bold text-primary">Faça Login</a></p>
            </div>
        </div>
    </div>
</div>

<style>
    .hover-scale { transition: transform 0.2s; }
    .hover-scale:hover { transform: scale(1.02); }
</style>

<script>
    function previewImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('preview-placeholder').classList.add('d-none');
                let img = document.getElementById('preview-foto');
                img.src = e.target.result;
                img.classList.remove('d-none');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function togglePassword(inputId) {
        var input = document.getElementById(inputId);
        var icon = input.parentElement.querySelector('.bi-eye, .bi-eye-slash');

        if (input.type === "password") {
            input.type = "text";
            icon.classList.remove('bi-eye');
            icon.classList.add('bi-eye-slash');
        } else {
            input.type = "password";
            icon.classList.remove('bi-eye-slash');
            icon.classList.add('bi-eye');
        }
    }
</script>

{{-- Scripts e Máscaras --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>

{{-- Script inline para garantir que as máscaras sejam aplicadas --}}
<script>
    $(document).ready(function(){
        $('.cpf-mask').mask('000.000.000-00', {reverse: true});
        $('.phone-mask').mask('(00) 00000-0000');
    });
</script>

<script src="{{ asset('js/location.js') }}"></script>
@endsection