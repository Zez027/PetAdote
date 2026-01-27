@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-primary">
            <i class="bi bi-folder2-open"></i> Gerenciar Meus Pets
        </h2>
        <a href="{{ route('pets.create') }}" class="btn btn-success shadow-sm">
            <i class="bi bi-plus-lg"></i> Cadastrar Novo Pet
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($pets->isEmpty())
        <div class="card border-0 shadow-sm text-center py-5">
            <div class="card-body">
                <div class="mb-3 text-muted" style="font-size: 3rem;">
                    <i class="bi bi-paw"></i>
                </div>
                <h4 class="text-muted">Você ainda não cadastrou nenhum pet.</h4>
                <p class="mb-4">Comece agora mesmo a ajudar um animalzinho a encontrar um lar!</p>
                <a href="{{ route('pets.create') }}" class="btn btn-primary">Cadastrar Primeiro Pet</a>
            </div>
        </div>
    @else
        <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-secondary">
                        <tr>
                            <th class="ps-4 py-3">Pet</th>
                            <th>Características</th>
                            <th>Localização</th>
                            <th>Status</th>
                            <th class="text-end pe-4">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pets as $pet)
                            @php
                                // Lógica da foto (igual você já tinha, mas simplificada)
                                $foto = $pet->photos->where('is_main', true)->first() ?? $pet->photos->first();
                                $fotoUrl = $foto ? asset('storage/' . $foto->foto) : asset('images/sem-foto.png');
                            @endphp
                            <tr>
                                <td class="ps-4 py-3">
                                    <div class="d-flex align-items-center">
                                        <img src="{{ $fotoUrl }}" 
                                             class="rounded-circle shadow-sm me-3" 
                                             width="50" height="50" 
                                             style="object-fit: cover; border: 2px solid #fff;">
                                        <div>
                                            <div class="fw-bold text-dark">{{ $pet->nome }}</div>
                                            <small class="text-muted">Cadastrado em {{ $pet->created_at->format('d/m/Y') }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border">{{ ucfirst($pet->tipo) }}</span>
                                    <span class="badge bg-light text-dark border">{{ $pet->raca }}</span>
                                    <br>
                                    <small class="text-muted">{{ ucfirst($pet->genero) }} • {{ ucfirst($pet->porte) }}</small>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center text-muted">
                                        <i class="bi bi-geo-alt-fill text-danger me-1"></i>
                                        {{ $pet->cidade }}
                                    </div>
                                </td>
                                <td>
                                    @if($pet->status == 'disponivel')
                                        <span class="badge bg-success-subtle text-success border border-success px-3 rounded-pill">
                                            Disponível
                                        </span>
                                    @elseif($pet->status == 'adotado')
                                        <span class="badge bg-secondary-subtle text-secondary border border-secondary px-3 rounded-pill">
                                            Adotado
                                        </span>
                                    @else
                                        <span class="badge bg-warning-subtle text-warning border border-warning px-3 rounded-pill">
                                            Indisponível
                                        </span>
                                    @endif
                                </td>
                                <td class="text-end pe-4 text-nowrap">
                                        <div class="d-flex gap-2 justify-content-end align-items-center">
                                            
                                            {{-- Botão VER (Fundo claro, Ícone azul) --}}
                                            <a href="{{ route('pets.show', $pet->id) }}" class="btn btn-sm btn-light shadow-sm" title="Ver no site">
                                                <i class="bi bi-eye text-primary"></i>
                                            </a>

                                            {{-- Botão EDITAR (Fundo claro, Ícone cinza/escuro) --}}
                                            <a href="{{ route('pets.edit', $pet->id) }}" class="btn btn-sm btn-light shadow-sm" title="Editar dados">
                                                <i class="bi bi-pencil text-dark"></i>
                                            </a>

                                            {{-- Botão EXCLUIR (Fundo claro, Ícone vermelho) --}}
                                            <form action="{{ route('pets.destroy', $pet->id) }}" method="POST" 
                                                class="d-inline m-0 p-0"
                                                onsubmit="return confirm('Tem certeza que deseja excluir {{ $pet->nome }}?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-light shadow-sm" title="Excluir permanentemente">
                                                    <i class="bi bi-trash text-danger"></i>
                                                </button>
                                            </form>

                                        </div>
                                    </td>
                                </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            {{-- Paginação --}}
            <div class="d-flex justify-content-center py-3">
                {{ $pets->links() }}
            </div>
        </div>
    @endif
</div>
@endsection