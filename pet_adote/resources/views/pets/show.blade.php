@extends('layouts.app')

@section('content')
<div class="container py-4">

    {{-- TÍTULO --}}
    <h2 class="mb-4 text-center fw-bold text-success">
        {{ $pet->nome }}
    </h2>

    <div class="row g-4">

        {{-- FOTOS EM CARROSSEL --}}
        <div class="col-md-6">
            <div id="petCarousel" class="carousel slide shadow-sm rounded" data-bs-ride="carousel">

                <div class="carousel-inner">

                    @foreach ($pet->photos as $index => $photo)
                        <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                            <img 
                                src="{{ asset('storage/' . $photo->foto) }}" 
                                class="d-block w-100 rounded"
                                style="object-fit: cover; height: 400px;"
                            >
                        </div>
                    @endforeach

                    @if($pet->photos->isEmpty())
                        <div class="carousel-item active">
                            <img 
                                src="{{ asset('images/sem-foto.png') }}" 
                                class="d-block w-100 rounded"
                                style="object-fit: cover; height: 400px;"
                            >
                        </div>
                    @endif

                </div>

                {{-- Controles --}}
                @if($pet->photos->count() > 1)
                    <button class="carousel-control-prev" type="button" data-bs-target="#petCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon"></span>
                    </button>

                    <button class="carousel-control-next" type="button" data-bs-target="#petCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon"></span>
                    </button>
                @endif

            </div>
        </div>

        {{-- INFORMAÇÕES DO PET --}}
        <div class="col-md-6">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-header">
                    <h5 class="mb-0 text-white">Informações do Pet</h5>
                </div>
                <div class="card-body">

                    <p><strong>Idade:</strong> {{ $pet->idade }} anos</p>
                    <p><strong>Gênero:</strong> {{ $pet->genero }}</p>
                    <p><strong>Raça:</strong> {{ $pet->raca }}</p>
                    <p><strong>Porte:</strong> {{ ucfirst($pet->porte) }}</p>
                    <p><strong>Tipo:</strong> {{ ucfirst($pet->tipo) }}</p>

                    <p class="mt-3"><strong>Localização:</strong><br>
                        📍 {{ $pet->cidade }}, {{ $pet->estado }}
                    </p>

                    <p class="mt-3"><strong>Descrição:</strong><br>
                        {{ $pet->descricao }}
                    </p>

                </div>
            </div>
        </div>
    </div>

    {{-- INFORMAÇÕES DO DOADOR --}}
    <h3 class="mt-5 mb-3 text-success fw-bold">Contato do Doador</h3>

    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-body">

            <p><strong>Nome:</strong> {{ $pet->user->full_name ?? $pet->user->name }}</p>

            <p><strong>Cidade:</strong> {{ $pet->user->cidade ?? $pet->user->cidade }}
         - {{$pet->user->estado ?? $pet->user->estado}}</p>

            @if($pet->user->email)
                <p><strong>Email:</strong>
                    <a href="mailto:{{ $pet->user->email }}" class="text-success fw-bold">
                        {{ $pet->user->email }}
                    </a>
                </p>
            @endif

            @if($pet->user->contato)
                <p><strong>Whatsapp:</strong>
                    <a href="https://wa.me/55{{ preg_replace('/\D/', '', $pet->user->contato) }}"
                       target="_blank" class="text-success fw-bold">
                        {{ $pet->user->contato }}
                    </a>
                </p>
            @endif


            @if($pet->user->instagram)
                <p><strong>Instagram:</strong>
                    <a href="https://instagram.com/{{ $pet->user->instagram }}"
                       target="_blank" class="text-success fw-bold">
                       Perfil no Instagram
                    </a>
                </p>
            @endif

            @if($pet->user->facebook)
                <p><strong>Facebook:</strong>
                    <a href="{{ $pet->user->facebook }}"
                       target="_blank" class="text-success fw-bold">
                        Perfil no Facebook
                    </a>
                </p>
            @endif

        </div>
    </div>

</div>
@endsection
