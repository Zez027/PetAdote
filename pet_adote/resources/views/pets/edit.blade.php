@extends('layouts.app')

@section('content')
<div class="container">

    <h2 class="mb-4">Editar Pet</h2>

    {{-- Mensagem de sucesso --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Mensagens de erro --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Corrija os erros abaixo:</strong>
            <ul class="mb-0">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Formulário de edição --}}
    <form action="{{ route('pets.update', $pet->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row">
            {{-- Nome --}}
            <div class="col-md-6 mb-3">
                <label class="form-label">Nome</label>
                <input type="text" name="nome" class="form-control" value="{{ old('nome', $pet->nome) }}" required>
            </div>

            {{-- Idade --}}
            <div class="col-md-3 mb-3">
                <label class="form-label">Idade</label>
                <input type="number" name="idade" class="form-control" value="{{ old('idade', $pet->idade) }}" required>
            </div>

            {{-- Gênero --}}
            <div class="col-md-3 mb-3">
                <label class="form-label">Gênero</label>
                <select name="genero" class="form-control" required>
                    <option value="">Selecione...</option>
                    <option value="Macho" {{ old('genero', $pet->genero) == 'Macho' ? 'selected' : '' }}>Macho</option>
                    <option value="Fêmea" {{ old('genero', $pet->genero) == 'Fêmea' ? 'selected' : '' }}>Fêmea</option>
                </select>
            </div>

            {{-- Porte --}}
            <div class="col-md-4 mb-3">
                <label class="form-label">Porte</label>
                <select name="porte" class="form-control" required>
                    <option value="">Selecione...</option>
                    <option value="pequeno" {{ old('porte', $pet->porte) == 'pequeno' ? 'selected' : '' }}>Pequeno</option>
                    <option value="medio" {{ old('porte', $pet->porte) == 'medio' ? 'selected' : '' }}>Médio</option>
                    <option value="grande" {{ old('porte', $pet->porte) == 'grande' ? 'selected' : '' }}>Grande</option>
                </select>
            </div>

            {{-- Tipo --}}
            <div class="col-md-4 mb-3">
                <label class="form-label">Tipo</label>
                <select name="tipo" class="form-control" required>
                    <option value="">Selecione...</option>
                    @foreach($types as $type)
                        <option value="{{ $type }}" {{ old('tipo', $pet->tipo) == $type ? 'selected' : '' }}>{{ $type }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Raça --}}
            <div class="col-md-4 mb-3">
                <label class="form-label">Raça</label>
                <input type="text" name="raca" class="form-control" value="{{ old('raca', $pet->raca) }}" required>
            </div>

            {{-- Localização --}}
            <div class="col-md-4 mb-3">
                <label class="form-label">País</label>
                <input type="text" name="pais" class="form-control" value="Brasil" readonly>
            </div>

            <div class="col-md-4 mb-3">
                <label class="form-label">Estado</label>
                <select name="estado" id="estado" class="form-control" data-value="{{ old('estado', $pet->estado) }}" required>
                    <option value="">Selecione...</option>
                </select>
            </div>

            <div class="col-md-4 mb-3">
                <label class="form-label">Cidade</label>
                <select name="cidade" id="cidade" class="form-control" data-value="{{ old('cidade', $pet->cidade) }}" required>
                    <option value="">Selecione...</option>
                </select>
            </div>

            <div class="col-md-4 mb-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-control" required>
                    <option value="disponivel" {{ old('status', $pet->status) == 'disponivel' ? 'selected' : '' }}>Disponível</option>
                    <option value="indisponivel" {{ old('status', $pet->status) == 'indisponivel' ? 'selected' : '' }}>Indisponível</option>
                    <option value="adotado" {{ old('status', $pet->status) == 'adotado' ? 'selected' : '' }}>Adotado</option>
                </select>
            </div>

            {{-- Descrição --}}
            <div class="col-md-12 mb-3">
                <label class="form-label">Descrição</label>
                <textarea name="descricao" class="form-control" rows="3">{{ old('descricao', $pet->descricao) }}</textarea>
            </div>

            {{-- Fotos existentes --}}
            <div class="col-md-12 mb-3">
                <label class="form-label">Fotos do Pet</label>
                <div class="row">
                    @foreach($pet->photos as $photo)
                        <div class="col-md-3 text-center mb-3">
                            <img src="{{ asset('storage/'.$photo->foto) }}" class="img-fluid rounded mb-2"
                                 style="{{ $photo->is_main ? 'border:3px solid green;' : '' }}">

                            {{-- Radio para selecionar principal --}}
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="main_photo_id" value="{{ $photo->id }}"
                                    {{ $photo->is_main ? 'checked' : '' }}>
                                <label class="form-check-label">Principal</label>
                            </div>

                            {{-- Botão excluir via AJAX --}}
                            <button type="button" class="btn btn-danger btn-sm w-100 mt-1" onclick="deletePhoto({{ $photo->id }}, this)">Excluir</button>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Adicionar novas fotos --}}
            <div class="col-md-12 mb-3">
                <label class="form-label">Adicionar novas fotos</label>
                <input type="file" name="photos[]" multiple class="form-control">
            </div>

        </div>

        {{-- Botão Salvar --}}
        <button type="submit" class="btn btn-success w-100 mt-3">Salvar</button>
    </form>
</div>

<script src="/js/location.js"></script>
<script>
function deletePhoto(photoId, btn) {
    if(!confirm('Deseja realmente excluir esta foto?')) return;
    
    fetch('/pets/photo/' + photoId, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        }
    })
    .then(res => res.json())
    .then(data => {
        if(data.success){
            btn.closest('div.col-md-3').remove();
        }
    })
    .catch(err => console.error(err));
}
</script>
@endsection
