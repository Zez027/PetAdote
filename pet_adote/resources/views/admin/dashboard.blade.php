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

            <div class="row mb-4">
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

            <div class="row">
                <div class="col-12 mb-4">
                    <div class="card shadow-sm border-0 rounded-3 h-100">
                        <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                            <h6 class="m-0 fw-bold text-dark">Evolução de Adoções</h6>
                            <select id="seletorPeriodo" class="form-select form-select-sm w-auto shadow-sm">
                                <option value="mensal" selected>Mensal (Este Ano)</option>
                                <option value="bimestral">Bimestral (Este Ano)</option>
                                <option value="trimestral">Trimestral (Este Ano)</option>
                                <option value="anual">Anual (Últimos 5 anos)</option>
                            </select>
                        </div>
                        <div class="card-body p-4">
                            <canvas id="graficoEvolucao" style="height: 350px; width: 100%;"></canvas>
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
        
        // --- 1. GRÁFICO DE BARRAS (STATUS ADOÇÕES) ---
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
                        'rgba(54, 162, 235, 0.7)',
                        'rgba(75, 192, 192, 0.7)',
                        'rgba(255, 99, 132, 0.7)',
                        'rgba(255, 206, 86, 0.7)',
                        'rgba(153, 102, 255, 0.7)'
                    ],
                    borderWidth: 1,
                    borderRadius: 5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
            }
        });

        // --- 2. GRÁFICO DE ROSCA (PETS POR ESPÉCIE) ---
        const dadosPets = @json($petsPorEspecie);
        const labelsPets = Object.keys(dadosPets);
        const valoresPets = Object.values(dadosPets);

        new Chart(document.getElementById('graficoPets'), {
            type: 'doughnut',
            data: {
                labels: labelsPets,
                datasets: [{
                    data: valoresPets,
                    backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b'],
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: { legend: { position: 'bottom' } }
            }
        });

        // --- 3. GRÁFICO DE LINHA (EVOLUÇÃO TEMPORAL) ---
        let graficoEvolucaoChart; // Guarda a instância do gráfico para podermos atualizá-lo depois

        function carregarDadosEvolucao(periodo) {
            // Fazer a requisição AJAX para buscar os dados baseados no período escolhido
            fetch(`{{ route('admin.chart_data') }}?periodo=${periodo}`)
                .then(response => response.json())
                .then(dados => {
                    // Se o gráfico já existir, destrói para criar um novo com os novos dados
                    if (graficoEvolucaoChart) {
                        graficoEvolucaoChart.destroy();
                    }

                    const ctxEvolucao = document.getElementById('graficoEvolucao').getContext('2d');
                    
                    // Criar um gradiente bonitinho para preencher debaixo da linha
                    let gradiente = ctxEvolucao.createLinearGradient(0, 0, 0, 400);
                    gradiente.addColorStop(0, 'rgba(78, 115, 223, 0.5)'); // Azul forte no topo
                    gradiente.addColorStop(1, 'rgba(78, 115, 223, 0.0)'); // Transparente em baixo

                    graficoEvolucaoChart = new Chart(ctxEvolucao, {
                        type: 'line',
                        data: {
                            labels: dados.labels,
                            datasets: [{
                                label: 'Adoções realizadas',
                                data: dados.data,
                                borderColor: '#4e73df', // Linha azul
                                backgroundColor: gradiente, // Preenchimento suave
                                borderWidth: 3,
                                pointBackgroundColor: '#4e73df',
                                pointBorderColor: '#fff',
                                pointHoverBackgroundColor: '#fff',
                                pointHoverBorderColor: '#4e73df',
                                pointRadius: 4,
                                pointHoverRadius: 6,
                                fill: true,
                                tension: 0.3 // Deixa a linha curvadinha e suave
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: { legend: { display: false } },
                            scales: { 
                                y: { 
                                    beginAtZero: true, 
                                    ticks: { precision: 0 },
                                    grid: { color: "rgba(0, 0, 0, 0.05)" } 
                                },
                                x: {
                                    grid: { display: false } // Esconde as linhas verticais do fundo
                                }
                            }
                        }
                    });
                })
                .catch(erro => console.error("Erro ao carregar o gráfico de evolução:", erro));
        }

        // Carregar o gráfico inicialmente (Mensal)
        carregarDadosEvolucao('mensal');

        // Adicionar evento para quando o administrador alterar a caixinha (select)
        document.getElementById('seletorPeriodo').addEventListener('change', function() {
            carregarDadosEvolucao(this.value);
        });

    });
</script>
@endsection