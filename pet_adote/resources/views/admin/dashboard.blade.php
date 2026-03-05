@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    Menu Administrador
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="list-group-item"><a href="{{ route('admin.users.index') }}">Gerenciar Usuários</a></li>
                    <li class="list-group-item text-muted">Gerenciar Pets (Em breve)</li>
                    <li class="list-group-item text-muted">Adoções (Em breve)</li>
                </ul>
            </div>
        </div>
        
        <div class="col-md-9">
            <div class="card">
                <div class="card-header">
                    <h2>Painel de Controle</h2>
                </div>
                <div class="card-body">
                    <p>Bem-vindo ao painel administrativo, {{ Auth::user()->name }}!</p>
                    <p>A partir daqui poderá gerir toda a plataforma.</p>
                    
                    <div class="row mt-4">
                        <div class="col-md-4">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <h5 class="card-title">Total de Pets</h5>
                                    <h2 class="text-primary">{{ $totalPets }}</h2> 
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <h5 class="card-title">Utilizadores</h5>
                                    <h2 class="text-success">{{ $totalUsers }}</h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <h5 class="card-title">Adoções Aprovadas</h5>
                                    <h2 class="text-info">{{ $totalAdocoes }}</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection