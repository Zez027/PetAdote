<?php

namespace App\Http\Controllers;

use App\Models\Pet;
use App\Models\PetPhoto;
use Illuminate\Http\Request;

class PetController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index','show']);
    }

    // Lista todos os pets (página inicial) com filtro
    public function index(Request $request)
    {
        $query = Pet::with('photos');

        if ($request->filled('cidade')) {
            $query->where('cidade', $request->cidade);
        }
        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $pets = $query->get();
        $cidades = Pet::select('cidade')->distinct()->pluck('cidade');
        $tipos = Pet::select('tipo')->distinct()->pluck('tipo');
        $statuses = ['disponivel', 'indisponivel', 'adotado'];

        return view('pets.index', compact('pets', 'cidades', 'tipos', 'statuses'));
    }

    // Lista apenas os pets do usuário logado
    public function meusPets()
    {
        $pets = Pet::with('photos')->where('user_id', auth()->id())->get();

        return view('pets.meus-pets', compact('pets'));
    }

    public function create()
    {
        return view('pets.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nome' => 'required|string|max:255',
            'idade' => 'required|integer|min:0',
            'porte' => 'required|string',
            'raca' => 'required|string',
            'tipo' => 'required|string',
            'descricao' => 'nullable|string',
            'status' => 'required|string',
            'pais' => 'required|string',
            'estado' => 'required|string',
            'cidade' => 'required|string',
        ]);
    
        $data['user_id'] = auth()->id();
    
        $pet = Pet::create($data);

        if($request->hasFile('photos')){
            foreach($request->file('photos') as $photo){
                $path = $photo->store('pets', 'public');
                PetPhoto::create(['foto'=>$path, 'pet_id'=>$pet->id]);
            }
        }

        return redirect()->route('pets.meus')->with('success', 'Pet cadastrado com sucesso!');;
    }

    public function show(Pet $pet)
    {
        $pet->load('photos','user');
        return view('pets.show', compact('pet'));
    }

    public function edit(Pet $pet)
    {
        // Verifica se o pet pertence ao usuário logado
        if ($pet->user_id !== auth()->id()) {
            abort(403);
        }
        return view('pets.edit', compact('pet'));
    }

    public function update(Request $request, Pet $pet)
    {
        if ($pet->user_id !== auth()->id()) {
            abort(403);
        }

        $data = $request->validate([
            'nome' => 'required|string|max:255',
            'idade' => 'required|integer|min:0',
            'porte' => 'required|string',
            'raca' => 'required|string',
            'tipo' => 'required|string',
            'descricao' => 'nullable|string',
            'status' => 'required|string',
            'pais' => 'required|string',
            'estado' => 'required|string',
            'cidade' => 'required|string',
        ]);

        $pet->update($data);

        if($request->hasFile('photos')){
            foreach($request->file('photos') as $photo){
                $path = $photo->store('pets', 'public');
                PetPhoto::create(['foto'=>$path, 'pet_id'=>$pet->id]);
            }
        }

        return redirect()->route('pets.meus')->with('success', 'Pet atualizado com sucesso!');
    }

    public function destroy(Pet $pet)
    {
        if ($pet->user_id !== auth()->id()) {
            abort(403);
        }
        $pet->delete();
        return redirect()->route('pets.meus')->with('success','Pet excluído!');
    }
}
