@extends('layouts.app')

@section('content')
<div class="container-fluid mt-4 px-xl-5">
    <div class="row">
        <div class="col-md-2 mb-4">
            <div class="bg-white rounded-3 shadow-sm border-0 h-100 p-3 sticky-top" style="top: 20px; z-index: 100;">
                <h6 class="text-uppercase text-muted fw-bold mb-3 small ms-2 mt-2">Painel Admin</h6>
                <ul class="nav flex-column nav-pills gap-1">
                    <li class="nav-item"><a class="nav-link text-dark" href="{{ route('admin.dashboard') }}"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link text-dark" href="{{ route('admin.users.index') }}"><i class="bi bi-people me-2"></i> Usuários</a></li>
                    <li class="nav-item"><a class="nav-link text-dark" href="{{ route('admin.pets.index') }}"><i class="bi bi-suit-heart me-2"></i> Pets</a></li>
                    <li class="nav-item"><a class="nav-link text-dark" href="{{ route('admin.adoptions.index') }}"><i class="bi bi-clipboard2-heart me-2"></i> Adoções</a></li>
                    <li class="nav-item"><a class="nav-link active bg-primary" href="{{ route('admin.reports.index') }}"><i class="bi bi-flag me-2"></i> Denúncias</a></li>
                </ul>
            </div>
        </div>

        <div class="col-md-10">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="h3 mb-0 text-dark fw-bold">Gestão de Denúncias</h2>
            </div>

            @if(session('success'))
                <div class="alert alert-success shadow-sm rounded-3"><i class="bi bi-check-circle me-2"></i>{{ session('success') }}</div>
            @endif

            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="py-3 ps-4 border-0 text-secondary fw-semibold">ID</th>
                                    <th class="py-3 border-0 text-secondary fw-semibold">Data</th>
                                    <th class="py-3 border-0 text-secondary fw-semibold">Denunciante</th>
                                    <th class="py-3 border-0 text-secondary fw-semibold">Alvo</th>
                                    <th class="py-3 border-0 text-secondary fw-semibold">Motivo</th>
                                    <th class="py-3 border-0 text-secondary fw-semibold">Estado</th>
                                    <th class="py-3 pe-4 border-0 text-secondary fw-semibold text-end">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($reports as $report)
                                    <tr>
                                        <td class="ps-4 text-muted fw-bold">#{{ $report->id }}</td>
                                        <td><small class="text-muted">{{ $report->created_at->format('d/m/Y H:i') }}</small></td>
                                        
                                        <td>
                                            @if($report->user)
                                                <a href="{{ route('admin.users.show', $report->user->id) }}" class="text-decoration-none fw-bold text-dark">{{ $report->user->name }}</a>
                                            @else
                                                <span class="text-muted fst-italic">Utilizador Apagado</span>
                                            @endif
                                        </td>
                                        
                                        <td>
                                            @if($report->reportable_type === 'App\Models\Pet' && $report->reportable)
                                                <span class="badge bg-info text-dark bg-opacity-10 border border-info px-2 py-1"><i class="bi bi-suit-heart-fill text-info me-1"></i> Pet</span>
                                                <a href="{{ route('admin.pets.show', $report->reportable->id) }}" class="text-decoration-none ms-1">{{ Str::limit($report->reportable->nome, 15) }}</a>
                                            @elseif($report->reportable_type === 'App\Models\User' && $report->reportable)
                                                <span class="badge bg-secondary text-dark bg-opacity-10 border border-secondary px-2 py-1"><i class="bi bi-person-fill text-secondary me-1"></i> Utilizador</span>
                                                <a href="{{ route('admin.users.show', $report->reportable->id) }}" class="text-decoration-none ms-1">{{ Str::limit($report->reportable->name, 15) }}</a>
                                            @else
                                                <span class="text-muted fst-italic">Registo Apagado</span>
                                            @endif
                                        </td>
                                        
                                        <td>
                                            <span class="fw-bold text-danger">{{ $report->reason }}</span>
                                            @if($report->description)
                                                <i class="bi bi-info-circle ms-1 text-muted" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $report->description }}" style="cursor: help;"></i>
                                            @endif
                                        </td>
                                        
                                        <td>
                                            @php
                                                $badges = [
                                                    'pendente' => 'bg-warning text-dark',
                                                    'em_analise' => 'bg-primary text-white',
                                                    'resolvida' => 'bg-success text-white',
                                                    'descartada' => 'bg-secondary text-white'
                                                ];
                                                $labels = [
                                                    'pendente' => 'Pendente',
                                                    'em_analise' => 'Em Análise',
                                                    'resolvida' => 'Resolvida',
                                                    'descartada' => 'Descartada'
                                                ];
                                            @endphp
                                            <span class="badge {{ $badges[$report->status] }} px-2 py-1">
                                                {{ $labels[$report->status] }}
                                            </span>
                                        </td>
                                        
                                        <td class="pe-4 text-end">
                                            <form action="{{ route('admin.reports.update', $report->id) }}" method="POST" class="d-inline-block">
                                                @csrf
                                                @method('PATCH')
                                                <div class="input-group input-group-sm">
                                                    <select name="status" class="form-select form-select-sm" style="min-width: 120px;">
                                                        <option value="pendente" {{ $report->status == 'pendente' ? 'selected' : '' }}>Pendente</option>
                                                        <option value="em_analise" {{ $report->status == 'em_analise' ? 'selected' : '' }}>Em Análise</option>
                                                        <option value="resolvida" {{ $report->status == 'resolvida' ? 'selected' : '' }}>Resolvida</option>
                                                        <option value="descartada" {{ $report->status == 'descartada' ? 'selected' : '' }}>Descartada</option>
                                                    </select>
                                                    <button type="submit" class="btn btn-outline-secondary"><i class="bi bi-check2"></i></button>
                                                </div>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-5">
                                            <div class="text-muted">
                                                <i class="bi bi-shield-check display-4 d-block mb-3 opacity-50"></i>
                                                <p class="mb-0 fs-5">Tudo tranquilo! Não há denúncias registadas no sistema.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($reports->hasPages())
                    <div class="card-footer bg-white border-top py-3 d-flex justify-content-center">
                        {{ $reports->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function(){
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
    });
</script>
@endsection