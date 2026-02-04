@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            
            {{-- Cartão de Login --}}
            <div class="card border-0 shadow-sm rounded-4 p-4">
                
                {{-- Cabeçalho --}}
                <div class="text-center mb-4">
                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                         style="width: 70px; height: 70px;">
                        <i class="bi bi-person-circle" style="font-size: 2.5rem;"></i>
                    </div>
                    <h3 class="fw-bold text-dark">Bem-vindo de volta!</h3>
                    <p class="text-muted small">Insira suas credenciais para acessar.</p>
                </div>

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    {{-- E-mail --}}
                    <div class="mb-3">
                        <label for="email" class="form-label small text-muted fw-bold">E-MAIL</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="bi bi-envelope text-muted"></i>
                            </span>
                            {{-- A classe is-invalid deixa a borda vermelha se houver erro --}}
                            <input type="email" name="email" id="email" 
                                   class="form-control border-start-0 ps-0 @error('email') is-invalid @enderror" 
                                   value="{{ old('email') }}" required autofocus placeholder="seu@email.com">
                        </div>
                        
                        {{-- MENSAGEM DE ERRO AQUI --}}
                        @error('email')
                            <div class="text-danger small mt-1 fw-bold">
                                <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Senha --}}
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <label for="password" class="form-label small text-muted fw-bold">SENHA</label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-decoration-none small text-primary fw-bold">
                                    Esqueceu a senha?
                                </a>
                            @endif
                        </div>
                        
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="bi bi-key text-muted"></i>
                            </span>
                            <input type="password" name="password" id="password" 
                                   class="form-control border-start-0 border-end-0 ps-0 @error('password') is-invalid @enderror" 
                                   required placeholder="••••••••">
                            <span class="input-group-text bg-light border-start-0" onclick="toggleLoginPassword()" style="cursor:pointer;">
                                <i class="bi bi-eye text-primary" id="toggleIcon"></i>
                            </span>
                        </div>
                    </div>

                    {{-- Checkbox Lembrar-me --}}
                    <div class="mb-4 form-check">
                        <input type="checkbox" name="remember" id="remember" class="form-check-input" {{ old('remember') ? 'checked' : '' }}>
                        <label class="form-check-label small text-muted" for="remember">
                            Lembrar-me neste dispositivo
                        </label>
                    </div>

                    {{-- Botão de Login --}}
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary rounded-pill py-3 fw-bold shadow-sm hover-scale text-uppercase">
                            Entrar
                        </button>
                    </div>

                    {{-- Link para Cadastro --}}
                    <div class="text-center mt-4 pt-3 border-top">
                        <p class="text-muted small mb-0">Não tem uma conta?</p>
                        <a href="{{ route('register') }}" class="btn btn-link text-decoration-none fw-bold text-primary">
                            Criar nova conta
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .hover-scale { transition: transform 0.2s; }
    .hover-scale:hover { transform: scale(1.02); }
</style>

<script>
    function toggleLoginPassword() {
        var input = document.getElementById('password');
        var icon = document.getElementById('toggleIcon');

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