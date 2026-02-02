@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 text-center">
            <div class="card border-0 shadow-sm rounded-4 p-4">
                <div class="mb-4">
                    <i class="bi bi-envelope-check text-primary" style="font-size: 4rem;"></i>
                </div>
                <h3 class="fw-bold">Verifique seu e-mail</h3>
                <p class="text-muted">
                    Antes de continuar, por favor confirme seu endereço de e-mail clicando no link que acabamos de enviar para você.
                </p>

                @if (session('message'))
                    <div class="alert alert-success small">
                        Um novo link de verificação foi enviado para o seu e-mail.
                    </div>
                @endif

                <form method="POST" action="{{ route('verification.send') }}" class="mt-4">
                    @csrf
                    <button type="submit" class="btn btn-primary rounded-pill px-4">
                        Reenviar E-mail de Verificação
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection