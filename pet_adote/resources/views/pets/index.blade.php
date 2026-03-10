@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4 text-center">Pets para Adoção 🐶🐱</h1>

    {{-- Mensagem de sucesso --}}
    @if(session('success'))
        <div class="alert alert-success text-center">
            {{ session('success') }}
        </div>
    @endif

    {{-- Form de filtros --}}
    <form method="GET" action="{{ route('home') }}" class="mb-4">
        <div class="row g-2">

            <!-- Cidade -->
            <select name="cidade" id="cidade" class="form-select">
                <option value="">Cidade</option>
            </select>
            
            <!-- Estado -->
            <div class="col-md-2">
                <select name="estado" id="estado" class="form-select">
                    <option value="">Estado</option>
                </select>
            </div>

            <!-- Tipo -->
            <div class="col-md-2">
                <select name="tipo" class="form-select">
                    <option value="">Tipo</option>
                    <option value="Cachorro" {{ request('tipo') == 'Cachorro' ? 'selected' : '' }}>Cachorro</option>
                    <option value="Gato" {{ request('tipo') == 'Gato' ? 'selected' : '' }}>Gato</option>
                    <option value="Outro" {{ request('tipo') == 'Outro' ? 'selected' : '' }}>Outro</option>
                </select>
            </div>

            <!-- Porte -->
            <div class="col-md-2">
                <select name="porte" class="form-select">
                    <option value="">Porte</option>
                    <option value="pequeno" {{ request('porte') == 'pequeno' ? 'selected' : '' }}>Pequeno</option>
                    <option value="medio" {{ request('porte') == 'medio' ? 'selected' : '' }}>Médio</option>
                    <option value="grande" {{ request('porte') == 'grande' ? 'selected' : '' }}>Grande</option>
                </select>
            </div>

            <!-- Gênero -->
            <div class="col-md-2">
                <select name="genero" class="form-select">
                    <option value="">Gênero</option>
                    <option value="Macho" {{ request('genero') == 'Macho' ? 'selected' : '' }}>Macho</option>
                    <option value="Fêmea" {{ request('genero') == 'Fêmea' ? 'selected' : '' }}>Fêmea</option>
                </select>
            </div>

            <!-- Raça -->
            <div class="col-md-3">
                <input type="text" name="raca" class="form-control"
                    placeholder="Raça"
                    value="{{ request('raca') }}">
            </div>

            <!-- Filtrar -->
            <div class="col-md-2">
                <button class="btn btn-success w-100">Filtrar</button>
            </div>

            <!-- Limpar -->
            <div class="col-md-2">
                <a href="{{ route('home') }}" class="btn btn-secondary w-100">Limpar</a>
            </div>

        </div>
    </form>

    {{-- Listagem --}}
    <div class="row">
        @forelse($pets as $pet)
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm border-0 rounded-3">

                    {{-- Foto principal --}}
                    @php
                        $main = $pet->photos->where('is_main', true)->first();
                        $foto = $main->foto ?? ($pet->photos->first()->foto ?? null);
                    @endphp

                    @if($foto)
                        <img 
                            src="{{ asset('storage/' . $foto) }}" 
                            class="card-img-top"
                            alt="{{ $pet->nome }}"
                            style="object-fit: cover; height: 250px;"
                        >
                    @else
                        <img 
                            src="{{ asset('images/sem-foto.png') }}" 
                            class="card-img-top"
                            alt="Sem foto"
                            style="object-fit: cover; height: 250px;"
                        >
                    @endif

                    <div class="card-body text-center d-flex flex-column">
                        <h5 class="card-title">{{ $pet->nome }}</h5>
                        <p class="text-muted">{{ $pet->idade }} anos • {{ ucfirst($pet->porte) }}</p>
                        <p class="text-secondary small">📍 {{ $pet->cidade }}, {{ $pet->estado }}</p>
                        <p>{{ Str::limit($pet->descricao, 100) }}</p>
                        <div class="mt-auto">
                            <a href="{{ route('pets.show', $pet->id) }}" class="btn btn-outline-success">Ver detalhes</a>
                        </div>
                    </div>

                </div>
            </div>
        @empty
            <p class="text-center mt-5">Nenhum pet disponível 😢</p>
        @endforelse
    </div>

    <div class="mt-4 d-flex justify-content-center">
        {{ $pets->links() }}
    </div>

</div>

<style>
     /* Customização da Paginação PetAdote */
    .pagination {
        gap: 5px; /* Espaço entre os números */
    }

    .pagination .page-item .page-link {
        border-radius: 10px !important; /* Botões arredondados */
        border: none;
        color: #0d6efd; /* Cor azul do seu projeto */
        padding: 8px 16px;
        background-color: #f8f9fa;
        transition: all 0.3s ease;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }

    .pagination .page-item.active .page-link {
        background-color: #0d6efd !important; /* Azul escuro para página ativa */
        color: white !important;
        font-weight: bold;
        box-shadow: 0 4px 8px rgba(13, 110, 253, 0.3);
    }

    .pagination .page-item .page-link:hover {
        background-color: #e9ecef;
        transform: translateY(-2px); /* Efeito de levante */
        color: #0a58ca;
    }

    /* Esconder o texto "Showing X to Y of Z" para ficar mais minimalista */
    .pagination-wrapper nav > div:first-child {
        display: none !important;
    }
</style>

{{-- Inclui o location.js --}}
<script src="{{ asset('js/location.js') }}"></script>
<script>
    // Inicializa estados e cidades
    document.addEventListener('DOMContentLoaded', function() {
        const estadoSelect = document.getElementById('estado');
        const cidadeSelect = document.getElementById('cidade');

        // Preenche estados
        estados.forEach(e => {
            const option = document.createElement('option');
            option.value = e;
            option.textContent = e;
            if(e === "{{ request('estado') }}") option.selected = true;
            estadoSelect.appendChild(option);
        });

        // Preenche cidades ao mudar estado
        estadoSelect.addEventListener('change', function() {
            cidadeSelect.innerHTML = '<option value="">Cidade</option>';
            if(locationData[this.value]) {
                locationData[this.value].forEach(c => {
                    const option = document.createElement('option');
                    option.value = c;
                    option.textContent = c;
                    cidadeSelect.appendChild(option);
                });
            }
        });

        // Se já tiver estado selecionado, dispara change para preencher cidades
        if(estadoSelect.value) estadoSelect.dispatchEvent(new Event('change'));
    });
</script>
@endsection
