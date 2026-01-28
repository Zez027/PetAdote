<?php

namespace App\Http\Controllers;

use App\Models\Pet;
use App\Models\PetPhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PetController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }

    // LISTAGEM COM FILTROS
    public function index(Request $request)
    {
        $query = Pet::with(['photos', 'user'])->where('status', 'disponivel');

        // Filtros
        if ($request->filled('search')) {
            $query->where('nome', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }
        if ($request->filled('porte')) {
            $query->where('porte', $request->porte);
        }
        if ($request->filled('cidade')) {
            $query->where('cidade', $request->cidade);
        }

        $pets = $query->latest()->get();

        // Pega as cidades dinamicamente para o filtro
        $cidades = Pet::where('status', 'disponivel')->distinct()->pluck('cidade');

        return view('home', compact('pets', 'cidades'));
    }

    // FORMULÁRIO DE CADASTRO DE PET
    public function create()
    {
        // Tipos de pets
        $types = [
            'Cachorro', 'Gato', 'Pássaro', 'Coelho',
            'Roedor', 'Exótico', 'Outro'
        ];

        // Status do pet
        $statuses = ['disponivel', 'indisponivel', 'adotado'];

        return view('pets.create', compact('types', 'statuses'));
    }

    // SALVAR PET
    public function store(Request $request)
    {
        $request->validate([
            'nome'      => 'required|string|max:255',
            'idade'     => 'required|integer',
            'genero'    => 'required|string',
            'porte'     => 'required|string',
            'tipo'      => 'required|string|max:255',
            'raca'      => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'status'    => 'required|in:disponivel,indisponivel,adotado',

            // LOCALIZAÇÃO (required)
            'pais'      => 'required|string',
            'estado'    => 'required|string',
            'cidade'    => 'required|string',

            'photos.*'  => 'image|max:2048'
        ]);

        $pet = Pet::create([
            'user_id'   => auth()->id(),
            'nome'      => $request->nome,
            'idade'     => $request->idade,
            'genero'    => $request->genero,
            'porte'     => $request->porte,
            'tipo'      => $request->tipo,
            'raca'      => $request->raca,
            'descricao' => $request->descricao,

            'pais'      => $request->pais,
            'estado'    => $request->estado,
            'cidade'    => $request->cidade,

            'status'    => $request->status,
        ]);

        $data = $request->all();
        // Checkbox não marcado não envia valor, então forçamos false se não vier
        $data['vacinado'] = $request->has('vacinado');
        $data['castrado'] = $request->has('castrado');
        $data['vermifugado'] = $request->has('vermifugado');

        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('pets', 'public');
                $pet->photos()->create(['foto' => $path]);
            }
        }

        Pet::create($data);

        return redirect()->route('pets.index')->with('success', 'Pet cadastrado!');
    }

    // LISTAR MEUS PETS
    public function meusPets()
    {
        $pets = Pet::where('user_id', auth()->id())
            ->with('photos')
            ->latest()
            ->paginate(6);

        return view('pets.meus-pets', compact('pets'));
    }

    // MOSTRAR PET
    public function show($id)
    {
        $pet = Pet::with(['photos', 'user'])->findOrFail($id);
        return view('pets.show', compact('pet'));
    }

    public function favoritos()
    {
        // Usa o relacionamento 'favorites' que criamos no Model User
        $pets = auth()->user()->favorites()->latest()->get();
        
        return view('pets.favoritos', compact('pets'));
    }

    // FORMULÁRIO DE EDIÇÃO
    public function edit($id)
    {
        $pet = Pet::with('photos')->findOrFail($id);

        if ($pet->user_id != Auth::id()) {
            abort(403, 'Acesso negado');
        }

        $types = [
            'Cachorro', 'Gato', 'Pássaro', 'Coelho',
            'Roedor', 'Exótico', 'Outro'
        ];

        $statuses = ['disponivel', 'indisponivel', 'adotado'];

        return view('pets.edit', compact('pet', 'types', 'statuses'));
    }

    // ATUALIZAR PET
    public function update(Request $request, $id)
    {
        $pet = Pet::with('photos')->findOrFail($id);

        // Verifica se o usuário é dono do pet
        if ($pet->user_id != Auth::id()) {
            abort(403, 'Acesso negado');
        }

        // Validação
        $request->validate([
            'nome'      => 'required|string|max:255',
            'idade'     => 'required|integer',
            'genero'    => 'required|string',
            'porte'     => 'required|string',
            'tipo'      => 'required|string|max:255',
            'raca'      => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'status'    => 'required|in:disponivel,indisponivel,adotado',
            'pais'      => 'required|string',
            'estado'    => 'required|string',
            'cidade'    => 'required|string',
            'photos.*'  => 'image|max:2048',
            'main_photo_id' => 'nullable|exists:pet_photos,id',
        ]);
        
        $pet->update([
            'nome'      => $request->nome,
            'idade'     => $request->idade,
            'genero'    => $request->genero,
            'porte'     => $request->porte,
            'tipo'      => $request->tipo,
            'raca'      => $request->raca,
            'descricao' => $request->descricao,
            'status'    => $request->status,
            'pais'      => $request->pais,
            'estado'    => $request->estado,
            'cidade'    => $request->cidade,
        ]);

        // Atualiza foto principal
        if ($request->filled('main_photo_id')) {
            $pet->photos()->update(['is_main' => false]);

            $mainPhoto = $pet->photos()->where('id', $request->main_photo_id)->first();
            if ($mainPhoto) {
                $mainPhoto->is_main = true;
                $mainPhoto->save();
            }
        }

        // Salvar novas fotos
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $file) {
                $path = $file->store('pets', 'public');
                $pet->photos()->create(['foto' => $path]);
            }
        }

        return redirect()->route('pets.meus')->with('success', 'Pet atualizado com sucesso!');
    }

    // EXCLUIR PET
    public function destroy($id)
    {
        $pet = Pet::with('photos')->findOrFail($id);

        if ($pet->user_id != Auth::id()) {
            abort(403, 'Acesso negado');
        }

        foreach ($pet->photos as $photo) {
            if (Storage::disk('public')->exists($photo->foto)) {
                Storage::disk('public')->delete($photo->foto);
            }
            $photo->delete();
        }

        $pet->delete();

        return redirect()->route('pets.index')->with('success', 'Pet removido com sucesso!');
    }

    // EXCLUIR FOTO
    public function deletePhoto(PetPhoto $photo)
    {
        if ($photo->pet->user_id != Auth::id()) {
            abort(403);
        }
    
        if (Storage::disk('public')->exists($photo->foto)) {
            Storage::disk('public')->delete($photo->foto);
        }
    
        $photo->delete();
    
        return back()->with('success', 'Foto removida com sucesso!');
    }
    
    // DEFINIR FOTO PRINCIPAL
    public function setMainPhoto(PetPhoto $photo)
    {
        if ($photo->pet->user_id != Auth::id()) {
            abort(403);
        }
    
        PetPhoto::where('pet_id', $photo->pet_id)->update(['is_main' => false]);
    
        $photo->is_main = true;
        $photo->save();
    
        return redirect()->back()->with('success', 'Foto principal atualizada!');
    }
}
