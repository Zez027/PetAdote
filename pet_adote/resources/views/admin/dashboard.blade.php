@extends('layouts.app')

@section('content')
<div class="container-fluid mt-4 px-xl-5">
    <div class="row">
        
        <div class="col-md-2 mb-4">
            <div class="bg-white rounded-3 shadow-sm border-0 h-100 p-3 sticky-top" style="top: 20px; z-index: 100;">
                <h6 class="text-uppercase text-muted fw-bold mb-3 small ms-2 mt-2">Painel Admin</h6>
                <ul class="nav flex-column nav-pills gap-1">
                    <li class="nav-item">
                        <a class="nav-link active bg-primary" href="{{ route('admin.dashboard') }}">
                            <i class="bi bi-speedometer2 me-2"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-dark" href="{{ route('admin.users.index') }}">
                            <i class="bi bi-people me-2"></i> Usuários
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-dark" href="{{ route('admin.pets.index') }}">
                            <i class="bi bi-suit-heart me-2"></i> Pets
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-dark" href="{{ route('admin.adoptions.index') }}">
                            <i class="bi bi-clipboard2-heart me-2"></i> Adoções
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        
        <div class="col-md-10">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="h3 mb-0 text-dark fw-bold">Visão Geral</h2>
                <span class="text-muted">Bem-vindo, {{ Auth::user()->name }}</span>
            </div>

            <div class="row mb-4">
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-0 shadow-sm rounded-3 border-start border-primary border-4 h-100 py-2">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <div class="text-xs fw-bold text-primary text-uppercase mb-1" style="font-size: 0.8rem;">Total de Usuários</div>
                                    <div class="h3 mb-0 fw-bold text-dark">{{ $totalUsers }}</div>
                                </div>
                                <i class="bi bi-people text-gray-300 fs-1 text-muted opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-0 shadow-sm rounded-3 border-start border-success border-4 h-100 py-2">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <div class="text-xs fw-bold text-success text-uppercase mb-1" style="font-size: 0.8rem;">Pets Cadastrados</div>
                                    <div class="h3 mb-0 fw-bold text-dark">{{ $totalPets }}</div>
                                </div>
                                <i class="bi bi-suit-heart text-gray-300 fs-1 text-muted opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-0 shadow-sm rounded-3 border-info border-4 h-100 py-2">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <div class="text-xs fw-bold text-info text-uppercase mb-1" style="font-size: 0.8rem;">Pedidos de Adoção</div>
                                    <div class="h3 mb-0 fw-bold text-dark">{{ $totalAdocoes }}</div>
                                </div>
                                <i class="bi bi-clipboard-data text-gray-300 fs-1 text-muted opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-0 shadow-sm rounded-3 border-warning border-4 h-100 py-2">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <div class="text-xs fw-bold text-warning text-uppercase mb-1" style="font-size: 0.8rem;">Adoções Aprovadas</div>
                                    <div class="h3 mb-0 fw-bold text-dark">{{ $adocoesAprovadas }}</div>
                                </div>
                                <i class="bi bi-award text-gray-300 fs-1 text-muted opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-8 mb-4">
                    <div class="card shadow-sm border-0 rounded-3 h-100">
                        <div class="card-header bg-white border-bottom py-3">
                            <h6 class="m-0 fw-bold text-dark">Status das Solicitações de Adoção</h6>
                        </div>
                        <div class="card-body">
                            <canvas id="graficoAdocoes" style="max-height: 300px;"></canvas>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 mb-4">
                    <div class="card shadow-sm border-0 rounded-3 h-100">
                        <div class="card-header bg-white border-bottom py-3">
                            <h6 class="m-0 fw-bold text-dark">Distribuição de Pets</h6>
                        </div>
                        <div class="card-body d-flex align-items-center justify-content-center">
                            <canvas id="graficoPets" style="max-height: 250px;"></canvas>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        
        // --- 1. CONFIGURAÇÃO DO GRÁFICO DE ADOÇÕES (BARRAS) ---
        const dadosAdocoes = @json($adocoesPorStatus);
        const labelsAdocoes = Object.keys(dadosAdocoes).map(status => status.charAt(0).toUpperCase() + status.slice(1));
        const valoresAdocoes = Object.values(dadosAdocoes);

        new Chart(document.getElementById('graficoAdocoes'), {
            type: 'bar',
            data: {
                labels: labelsAdocoes,
                datasets: [{
                    label: 'Quantidade',
                    data: valoresAdocoes,
                    backgroundColor: [
                        'rgba(54, 162, 235, 0.7)',  // Azul (ex: pendente)
                        'rgba(75, 192, 192, 0.7)',  // Verde (ex: aprovado)
                        'rgba(255, 99, 132, 0.7)',  // Vermelho (ex: rejeitado)
                        'rgba(255, 206, 86, 0.7)',  // Amarelo
                        'rgba(153, 102, 255, 0.7)'  // Roxo
                    ],
                    borderWidth: 1,
                    borderRadius: 5 // Deixa a pontinha da barra arredondada
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } }, // Esconde a legenda
                scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
            }
        });

        // --- 2. CONFIGURAÇÃO DO GRÁFICO DE PETS (ROSCA / DOUGHNUT) ---
        const dadosPets = @json($petsPorEspecie);
        const labelsPets = Object.keys(dadosPets);
        const valoresPets = Object.values(dadosPets);

        new Chart(document.getElementById('graficoPets'), {
            type: 'doughnut',
            data: {
                labels: labelsPets,
                datasets: [{
                    data: valoresPets,
                    backgroundColor: [
                        '#4e73df', // Azul forte
                        '#1cc88a', // Verde forte
                        '#36b9cc', // Ciano
                        '#f6c23e', // Amarelo
                        '#e74a3b'  // Vermelho
                    ],
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%', // Espessura da rosca
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });

    });
</script>
@endsection