@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">

            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
                
                <div class="d-flex align-items-center mb-4 border-bottom pb-3">
                    <i class="bi bi-pencil-square text-primary me-2" style="font-size: 2rem;"></i>
                    <h3 class="fw-bold mb-0">Editar Pet: {{ $pet->nome }}</h3>
                </div>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-3" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger border-0 shadow-sm rounded-3">
                        <strong><i class="bi bi-exclamation-triangle-fill me-1"></i> Corrija os erros abaixo:</strong>
                        <ul class="mb-0 mt-2">
                            @foreach ($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('pets.update', $pet->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <h5 class="fw-bold mb-3 text-secondary"><i class="bi bi-info-circle me-1"></i> Informações Básicas</h5>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted">NOME DO PET</label>
                            <div class="input-group input-group-lg bg-light rounded-3">
                                <span class="input-group-text bg-transparent border-0"><i class="bi bi-tag text-secondary"></i></span>
                                <input type="text" name="nome" class="form-control bg-transparent border-0 ps-0" value="{{ old('nome', $pet->nome) }}" required>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label fw-bold small text-muted">IDADE (ANOS)</label>
                            <div class="input-group input-group-lg bg-light rounded-3">
                                <span class="input-group-text bg-transparent border-0"><i class="bi bi-calendar3 text-secondary"></i></span>
                                <input type="number" name="idade" class="form-control bg-transparent border-0 ps-0" value="{{ old('idade', $pet->idade) }}" required min="0">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label fw-bold small text-muted">GÊNERO</label>
                            <div class="input-group input-group-lg bg-light rounded-3">
                                <span class="input-group-text bg-transparent border-0"><i class="bi bi-gender-ambiguous text-secondary"></i></span>
                                <select name="genero" class="form-select bg-transparent border-0 ps-0" required>
                                    <option value="">Selecione...</option>
                                    <option value="Macho" {{ old('genero', $pet->genero) == 'Macho' ? 'selected' : '' }}>Macho</option>
                                    <option value="Fêmea" {{ old('genero', $pet->genero) == 'Fêmea' ? 'selected' : '' }}>Fêmea</option>
                                    <option value="Não Definido" {{ old('genero', $pet->genero) == 'Não Definido' ? 'selected' : '' }}>Não Definido</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold small text-muted">ESPÉCIE</label>
                            <div class="input-group input-group-lg bg-light rounded-3">
                                <span class="input-group-text bg-transparent border-0"><i class="bi bi-bug text-secondary"></i></span>
                                <select name="especie" class="form-select bg-transparent border-0 ps-0" id="especie" required onchange="atualizarRacasLocal()">
                                    <option value="">Selecione...</option>
                                    <option value="Cachorro" {{ old('especie', $pet->especie) == 'Cachorro' ? 'selected' : '' }}>Cachorro</option>
                                    <option value="Gato" {{ old('especie', $pet->especie) == 'Gato' ? 'selected' : '' }}>Gato</option>
                                    <option value="Pássaro" {{ old('especie', $pet->especie) == 'Pássaro' ? 'selected' : '' }}>Pássaro</option>
                                    <option value="Coelho" {{ old('especie', $pet->especie) == 'Coelho' ? 'selected' : '' }}>Coelho</option>
                                    <option value="Roedor" {{ old('especie', $pet->especie) == 'Roedor' ? 'selected' : '' }}>Roedor</option>
                                    <option value="Réptil" {{ old('especie', $pet->especie) == 'Réptil' ? 'selected' : '' }}>Réptil</option>
                                    <option value="Equino" {{ old('especie', $pet->especie) == 'Equino' ? 'selected' : '' }}>Equino</option>
                                    <option value="Outros" {{ old('especie', $pet->especie) == 'Outros' ? 'selected' : '' }}>Outros</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold small text-muted">RAÇA</label>
                            <div class="input-group input-group-lg bg-light rounded-3">
                                <span class="input-group-text bg-transparent border-0"><i class="bi bi-stars text-secondary"></i></span>
                                <select name="raca" class="form-select bg-transparent border-0 ps-0" id="raca" required data-selected="{{ old('raca', $pet->raca) }}">
                                    <option value="">Primeiro selecione a espécie</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold small text-muted">PORTE</label>
                            <div class="input-group input-group-lg bg-light rounded-3">
                                <span class="input-group-text bg-transparent border-0"><i class="bi bi-arrows-angle-expand text-secondary"></i></span>
                                <select name="porte" class="form-select bg-transparent border-0 ps-0" required>
                                    <option value="">Selecione...</option>
                                    <option value="Pequeno" {{ old('porte', $pet->porte) == 'Pequeno' ? 'selected' : '' }}>Pequeno</option>
                                    <option value="Médio" {{ old('porte', $pet->porte) == 'Médio' ? 'selected' : '' }}>Médio</option>
                                    <option value="Grande" {{ old('porte', $pet->porte) == 'Grande' ? 'selected' : '' }}>Grande</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <h5 class="fw-bold mb-3 text-secondary mt-4"><i class="bi bi-heart-pulse me-1"></i> Informações de Saúde</h5>
                    <div class="bg-light rounded-3 p-3 mb-4">
                        <div class="row mb-1">
                            <div class="col-md-4">
                                <div class="form-check form-switch form-check-lg">
                                    <input class="form-check-input" type="checkbox" name="vacinado" id="vacinado" value="1" {{ old('vacinado', $pet->vacinado) ? 'checked' : '' }}>
                                    <label class="form-check-label fw-bold text-muted" for="vacinado">Vacinado</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check form-switch form-check-lg">
                                    <input class="form-check-input" type="checkbox" name="castrado" id="castrado" value="1" {{ old('castrado', $pet->castrado) ? 'checked' : '' }}>
                                    <label class="form-check-label fw-bold text-muted" for="castrado">Castrado</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check form-switch form-check-lg">
                                    <input class="form-check-input" type="checkbox" name="vermifugado" id="vermifugado" value="1" {{ old('vermifugado', $pet->vermifugado) ? 'checked' : '' }}>
                                    <label class="form-check-label fw-bold text-muted" for="vermifugado">Vermifugado</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <h5 class="fw-bold mb-3 text-secondary mt-4"><i class="bi bi-geo-alt me-1"></i> Localização & Status</h5>
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label class="form-label fw-bold small text-muted">PAÍS</label>
                            <div class="input-group input-group-lg bg-light rounded-3">
                                <span class="input-group-text bg-transparent border-0"><i class="bi bi-globe-americas text-secondary"></i></span>
                                <select name="pais" class="form-select bg-transparent border-0 ps-0" required>
                                    <option value="Brasil" selected>Brasil</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold small text-muted">ESTADO</label>
                            <div class="input-group input-group-lg bg-light rounded-3">
                                <span class="input-group-text bg-transparent border-0"><i class="bi bi-map text-secondary"></i></span>
                                <select name="estado" id="estado" class="form-select bg-transparent border-0 ps-0" required data-selected="{{ old('estado', $pet->estado) }}">
                                    <option value="">Carregando...</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold small text-muted">CIDADE</label>
                            <div class="input-group input-group-lg bg-light rounded-3">
                                <span class="input-group-text bg-transparent border-0"><i class="bi bi-building text-secondary"></i></span>
                                <select name="cidade" id="cidade" class="form-select bg-transparent border-0 ps-0" required data-selected="{{ old('cidade', $pet->cidade) }}">
                                    <option value="">Selecione o estado</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted">STATUS DO PET</label>
                            <div class="input-group input-group-lg bg-light rounded-3">
                                <span class="input-group-text bg-transparent border-0"><i class="bi bi-clipboard-check text-secondary"></i></span>
                                <select name="status" class="form-select bg-transparent border-0 ps-0" required>
                                    <option value="Disponível" {{ old('status', $pet->status) == 'Disponível' ? 'selected' : '' }}>Disponível para Adoção</option>
                                    <option value="Em Processo" {{ old('status', $pet->status) == 'Em Processo' ? 'selected' : '' }}>Em Processo de Adoção</option>
                                    <option value="Adotado" {{ old('status', $pet->status) == 'Adotado' ? 'selected' : '' }}>Já Adotado</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <h5 class="fw-bold mb-3 text-secondary mt-5"><i class="bi bi-card-text me-1"></i> Mais Detalhes</h5>
                    <div class="row g-3 mb-4">
                        <div class="col-12">
                            <label class="form-label fw-bold small text-muted">DESCRIÇÃO DO PET</label>
                            <div class="bg-light rounded-3 p-2">
                                <textarea name="descricao" class="form-control bg-transparent border-0" rows="4" required>{{ old('descricao', $pet->descricao) }}</textarea>
                            </div>
                        </div>
                    </div>

                    <h5 class="fw-bold mb-3 text-secondary mt-5"><i class="bi bi-images me-1"></i> Adicionar Novas Fotos</h5>
                    <div class="row g-3 mb-4">
                        <div class="col-12">
                            <div class="bg-light rounded-3 p-3 border-dashed text-center" style="border: 2px dashed #dee2e6;">
                                <label class="form-label fw-bold small text-muted d-block mb-3">SELECIONE FOTOS PARA ADICIONAR (OPCIONAL)</label>
                                <input type="file" name="fotos[]" class="form-control form-control-lg bg-white" multiple accept="image/*">
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route('pets.meus') }}" class="btn btn-light btn-lg px-4 me-md-2 fw-bold text-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-primary btn-lg px-5 fw-bold text-white shadow-sm">Salvar Alterações</button>
                    </div>
                </form>
            </div>

            {{-- Gestão de Imagens Existentes --}}
            @if($pet->photos && $pet->photos->count() > 0)
            <div class="card border-0 shadow-sm rounded-4 p-4 mt-4">
                <div class="d-flex align-items-center mb-4 border-bottom pb-3">
                    <i class="bi bi-camera text-primary me-2" style="font-size: 1.5rem;"></i>
                    <h4 class="fw-bold mb-0">Gerenciar Fotos Existentes</h4>
                </div>

                <div class="row g-3">
                    @foreach($pet->photos as $photo)
                        <div class="col-6 col-md-4 col-lg-3">
                            <div class="card border-0 shadow-sm rounded-3 overflow-hidden position-relative">
                                <img src="{{ asset('storage/' . $photo->photo_path) }}" class="card-img-top object-fit-cover" style="height: 150px;" alt="Foto do Pet">
                                
                                @if($photo->is_main)
                                    <div class="position-absolute top-0 start-0 m-2">
                                        <span class="badge bg-success rounded-pill px-3 py-2 shadow-sm"><i class="bi bi-star-fill me-1"></i> Principal</span>
                                    </div>
                                @endif

                                <div class="card-body p-2 d-flex justify-content-between align-items-center bg-light">
                                    @if(!$photo->is_main)
                                        <form action="{{ route('pets.photo.main', $photo->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-primary fw-bold" title="Tornar principal">
                                                <i class="bi bi-star"></i>
                                            </button>
                                        </form>
                                    @else
                                        <div></div>
                                    @endif

                                    <form action="{{ route('pets.photo.delete', $photo->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Tem certeza que deseja excluir esta foto?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger fw-bold" title="Excluir foto">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

        </div>
    </div>

    <script src="{{ asset('js/masks.js') }}"></script>
    <script src="{{ asset('js/location.js') }}"></script>

    <script>
        const racasDict = {
            'Cachorro': ['SRD (Vira-lata)', 'Akita', 'Beagle', 'Boxer', 'Bulldog', 'Chihuahua', 'Dachshund', 'Golden Retriever', 'Husky', 'Labrador', 'Lulu da Pomerânia', 'Maltês', 'Pastor Alemão', 'Pinscher', 'Pitbull', 'Poodle', 'Pug', 'Rottweiler', 'Shih Tzu', 'Yorkshire', 'Outra'],
            'Gato': ['SRD (Sem Raça Definida)', 'Angorá', 'Bengal', 'Maine Coon', 'Persa', 'Ragdoll', 'Siamês', 'Sphynx', 'Outra'],
            'Pássaro': ['SRD', 'Calopsita', 'Canário', 'Papagaio', 'Periquito', 'Outra'],
            'Coelho': ['SRD', 'Angorá', 'Lionhead', 'Mini Lop', 'Outra'],
            'Roedor': ['Hamster', 'Porquinho-da-índia', 'Twister', 'Outro'],
            'Réptil': ['Cobra', 'Iguana', 'Jabuti', 'Tartaruga', 'Outro'],
            'Equino': ['SRD', 'Crioulo', 'Mangalarga', 'Quarto de Milha', 'Outra'],
            'Outros': ['Outra']
        };

        function atualizarRacasLocal(racaPreSelecionada = '') {
            const especie = document.getElementById('especie').value;
            const racaSelect = document.getElementById('raca');
            
            if(!racaSelect) return;

            racaSelect.innerHTML = '<option value="">Selecione a raça...</option>';
            
            if (racasDict[especie]) {
                racasDict[especie].forEach(raca => {
                    const option = document.createElement('option');
                    option.value = raca;
                    option.textContent = raca;
                    if (raca === racaPreSelecionada) option.selected = true;
                    racaSelect.appendChild(option);
                });
            }
        }

        document.addEventListener("DOMContentLoaded", function() {
            // Inicializa a raça lendo o valor gravado do banco de dados na view
            const especieElement = document.getElementById('especie');
            const racaElement = document.getElementById('raca');
            
            if(especieElement && especieElement.value) {
                atualizarRacasLocal(racaElement.getAttribute('data-selected'));
            }
        });
    </script>
</div>
@endsection