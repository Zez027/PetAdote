@extends('layouts.app')

@section('content')
<div class="container-fluid mt-4 px-xl-5">
    <div class="row">
        <div class="col-md-2 mb-4">
            <div class="bg-white rounded-3 shadow-sm border-0 h-100 p-3 sticky-top" style="top: 20px; z-index: 100;">
                <h6 class="text-uppercase text-muted fw-bold mb-3 small ms-2 mt-2">Painel Admin</h6>
                <ul class="nav flex-column nav-pills gap-1">
                    <li class="nav-item">
                        <a class="nav-link text-dark" href="{{ route('admin.dashboard') }}">
                            <i class="bi bi-speedometer2 me-2"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-dark" href="{{ route('admin.users.index') }}">
                            <i class="bi bi-people me-2"></i> Usuários
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active bg-primary" href="{{ route('admin.pets.index') }}">
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
                <h2 class="h3 mb-0 text-dark fw-bold">Gestão de Pets</h2>
            </div>

            @if(session('success'))
                <div class="alert alert-success shadow-sm rounded-3">{{ session('success') }}</div>
            @endif

            <div class="card shadow-sm border-0 rounded-3 mb-4">
                <div class="card-body p-4">
                    
                    <form action="{{ route('admin.pets.index') }}" method="GET" class="mb-4 bg-light p-3 rounded-3 border-0 shadow-sm">
                        <div class="row g-2 align-items-end">
                            <div class="col-md-6">
                                <label class="form-label small text-muted fw-bold mb-1">Buscar</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                                    <input type="text" name="search" class="form-control border-start-0 bg-white" placeholder="Nome, espécie, raça ou dono..." value="{{ request('search') }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small text-muted fw-bold mb-1">Status</label>
                                <select name="status" class="form-select border-0 shadow-sm">
                                    <option value="">Todos os Status</option>
                                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Ativos</option>
                                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inativos</option>
                                </select>
                            </div>
                            <div class="col-md-2 d-flex gap-2">
                                <button type="submit" class="btn btn-primary w-100 shadow-sm">Filtrar</button>
                                <a href="{{ route('admin.pets.index') }}" class="btn btn-outline-secondary bg-white shadow-sm" title="Limpar"><i class="bi bi-eraser"></i></a>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Foto</th>
                                    <th>Pet</th>
                                    <th>Espécie / Raça</th>
                                    <th>Responsável</th>
                                    <th>Status</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pets as $pet)
                                    <tr>
                                        <td><span class="text-muted fw-bold">#{{ $pet->id }}</span></td>
                                        <td>
                                            @if($pet->photos->count() > 0)
                                                <img src="{{ asset('storage/' . $pet->photos->first()->foto) }}" class="rounded-circle object-fit-cover shadow-sm border" width="45" height="45">
                                            @else
                                                <div class="rounded-circle bg-light d-flex align-items-center justify-content-center border" style="width: 45px; height: 45px;">
                                                    <i class="bi bi-camera text-muted"></i>
                                                </div>
                                            @endif
                                        </td>
                                        <td class="fw-bold">{{ $pet->nome }}</td>
                                        <td>
                                            <span class="badge bg-light text-dark border">{{ $pet->tipo }}</span><br>
                                            <small class="text-muted">{{ $pet->raca }}</small>
                                        </td>
                                        <td>
                                            @if($pet->user)
                                                <a href="{{ route('admin.users.show', $pet->user->id) }}" class="text-decoration-none">{{ $pet->user->name }}</a>
                                            @else
                                                <span class="text-muted fst-italic">Usuário Apagado</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($pet->trashed())
                                                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger">Inativo</span>
                                            @else
                                                <span class="badge bg-success bg-opacity-10 text-success border border-success">Ativo</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.pets.show', $pet->id) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                                Ficha do Pet
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4 text-muted">Nenhum pet encontrado.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4 d-flex justify-content-center">
                        {{ $pets->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection