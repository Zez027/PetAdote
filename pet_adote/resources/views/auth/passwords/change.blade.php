@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 p-4">
                <div class="mb-4 text-center">
                    <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <i class="bi bi-shield-lock text-primary" style="font-size: 1.8rem;"></i>
                    </div>
                    <h3 class="fw-bold">Alterar Senha</h3>
                    <p class="text-muted small">Crie uma senha forte para proteger sua conta.</p>
                </div>

                <form method="POST" action="{{ route('perfil.password.update') }}">
                    @csrf
                    @method('PUT')

                    {{-- Nova Senha --}}
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">Nova Senha</label>
                        <div class="input-group">
                            <input type="password" name="password" id="password" 
                                   class="form-control border-end-0 @error('password') is-invalid @enderror" required>
                            <button class="btn btn-outline-secondary border-start-0 bg-white" type="button" onclick="togglePassword('password', this)">
                                <i class="bi bi-eye"></i>
                            </button>
                            @error('password')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-text small">
                            Mínimo de 8 caracteres, com letras maiúsculas, minúsculas, números e símbolos.
                        </div>
                    </div>

                    {{-- Confirmar Nova Senha --}}
                    <div class="mb-4">
                        <label class="form-label fw-bold small text-muted">Confirmar Nova Senha</label>
                        <div class="input-group">
                            <input type="password" name="password_confirmation" id="password_confirmation" 
                                   class="form-control border-end-0" required>
                            <button class="btn btn-outline-secondary border-start-0 bg-white" type="button" onclick="togglePassword('password_confirmation', this)">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary rounded-pill fw-bold py-2">Atualizar Senha</button>
                        <a href="{{ route('perfil.edit') }}" class="btn btn-light rounded-pill py-2">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function togglePassword(inputId, btn) {
        const input = document.getElementById(inputId);
        const icon = btn.querySelector('i');
        
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