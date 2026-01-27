@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm border-0 rounded-3">
            <div class="card-header bg-primary text-white text-center py-3">
                <h4 class="mb-0 fw-bold">Criar Conta</h4>
            </div>
            <div class="card-body p-4">

                <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
                    @csrf

                    {{-- Seção de Dados Pessoais --}}
                    <h5 class="text-secondary mb-3 border-bottom pb-2">Dados Pessoais</h5>

                    <div class="row g-3 mb-3">
                        {{-- FOTO DE PERFIL --}}
                        <div class="col-12 text-center mb-2">
                            <label class="form-label fw-bold">Foto de Perfil (Opcional)</label>
                            <input type="file" name="profile_photo" class="form-control w-50 mx-auto @error('profile_photo') is-invalid @enderror">
                            @error('profile_photo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- NOME --}}
                        <div class="col-md-6">
                            <label class="form-label">Nome Completo <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" required value="{{ old('name') }}">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- EMAIL --}}
                        <div class="col-md-6">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" required value="{{ old('email') }}">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                       {{-- Campo CPF --}}
                        <div class="col-md-6">
                            <label class="form-label">CPF <span class="text-danger">*</span></label>
                            <input type="cpf" name="cpf" class="form-control @error('cpf') is-invalid @enderror" 
                                oninput="mascaraCPF(this)" maxlength="14" 
                                placeholder="000.000.000-00" value="{{ old('cpf') }}" required>
                        </div>

                        {{-- Campo Contato --}}
                        <div class="col-md-6">
                            <label class="form-label">Whatsapp / Contato <span class="text-danger">*</span></label>
                            <input type="cel" name="contato" class="form-control @error('contato') is-invalid @enderror" 
                                oninput="mascaraTelefone(this)" maxlength="14" 
                                placeholder="(00)000000000" value="{{ old('contato') }}" required>
                        </div>
                    </div>

                    {{-- Seção de Redes Sociais (NOVA) --}}
                    <h5 class="text-secondary mb-3 border-bottom pb-2 mt-4">Redes Sociais <small class="text-muted fw-normal fs-6">(Opcional)</small></h5>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label"><i class="bi bi-facebook text-primary"></i> Facebook (Link)</label>
                                <input type="url" name="facebook" class="form-control @error('facebook') is-invalid @enderror" 
                                    value="{{ old('facebook') }}" placeholder="https://facebook.com/seu.perfil">
                                @error('facebook')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label"><i class="bi bi-instagram text-danger"></i> Instagram (Link)</label>
                                <input type="url" name="instagram" class="form-control @error('instagram') is-invalid @enderror" 
                                    value="{{ old('instagram') }}" placeholder="https://instagram.com/seu.perfil">
                                @error('instagram')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                    <h5 class="text-secondary mb-3 border-bottom pb-2 mt-4">Endereço</h5>

                    {{-- Seção de Endereço --}}
                    <h5 class="text-secondary mb-3 border-bottom pb-2 mt-4">Endereço</h5>

                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="form-label">CEP (Opcional)</label>
                            <input type="text" id="cep" class="form-control" onblur="buscarCep(this.value)" placeholder="00000-000">
                        </div>
                        <div class="col-md-8">
                            </div>

                        <div class="col-md-4">
                            <label class="form-label">País <span class="text-danger">*</span></label>
                            <input type="text" name="pais" id="pais" class="form-control @error('pais') is-invalid @enderror" required value="{{ old('pais', 'Brasil') }}">
                            @error('pais')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Estado <span class="text-danger">*</span></label>
                            <input type="text" name="estado" id="estado" class="form-control @error('estado') is-invalid @enderror" required value="{{ old('estado') }}">
                            @error('estado')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Cidade <span class="text-danger">*</span></label>
                            <input type="text" name="cidade" id="cidade" class="form-control @error('cidade') is-invalid @enderror" required value="{{ old('cidade') }}">
                            @error('cidade')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Seção de Segurança --}}
                    <h5 class="text-secondary mb-3 border-bottom pb-2 mt-4">Segurança</h5>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label">Senha <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="password" name="password" id="senha" class="form-control @error('password') is-invalid @enderror" required>
                                <span class="input-group-text bg-white" onclick="togglePassword('senha')" style="cursor:pointer;">
                                    <i class="bi bi-eye text-primary"></i>
                                </span>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Confirmar Senha <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="password" name="password_confirmation" id="confirmar_senha" class="form-control" required>
                                <span class="input-group-text bg-white" onclick="togglePassword('confirmar_senha')" style="cursor:pointer;">
                                    <i class="bi bi-eye text-primary"></i>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg fw-bold">Registrar</button>
                    </div>
                </form>

            </div>
        </div>
        
        <div class="text-center mt-3">
            <p>Já tem uma conta? <a href="{{ route('login') }}" class="text-decoration-none fw-bold">Faça Login</a></p>
        </div>

    </div>
</div>

{{-- Scripts (Mantive os seus e adicionei a função da senha) --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
<script src="{{ asset('js/masks.js') }}"></script>
<script src="{{ asset('js/location.js') }}"></script>

<script>
    // Função para mostrar/esconder senha
    function togglePassword(inputId) {
        var input = document.getElementById(inputId);
        var icon = input.nextElementSibling.querySelector('i');

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
@endsection