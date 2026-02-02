<?php

namespace App\Http\Controllers;

use App\Models\AdoptionRequest;
use App\Models\Pet;
use App\Http\Requests\StoreAdoptionRequest; // Importando o novo Request
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdoptionController extends Controller
{
    /**
     * Lista solicitações recebidas (Doador vê quem quer adotar seus pets).
     */
    public function index()
    {
        $requests = AdoptionRequest::whereHas('pet', function ($query) {
            $query->where('user_id', Auth::id());
        })
        ->with(['pet', 'user'])
        ->latest()
        ->paginate(10); // Melhoria: Paginação

        return view('adoptions.index', compact('requests'));
    }

    /**
     * Cria uma nova solicitação com as validações solicitadas.
     */
    public function store(StoreAdoptionRequest $request)
    {
        // A validação do pet_id já aconteceu via StoreAdoptionRequest
        
        $pet = Pet::findOrFail($request->pet_id);
        $userId = Auth::id();

        // Melhoria: impede adotar o próprio pet
        if ($pet->user_id === $userId) {
            return redirect()->back()->with('error', 'Você não pode solicitar a adoção do seu próprio pet.');
        }

        // Melhoria: Validação de Solicitação Duplicada
        $exists = AdoptionRequest::where('user_id', $userId)
            ->where('pet_id', $request->pet_id)
            ->whereIn('status', ['pendente', 'aprovado'])
            ->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'Você já possui uma solicitação ativa para este pet.');
        }

        AdoptionRequest::create([
            'user_id' => $userId,
            'pet_id'  => $request->pet_id,
            'status'  => 'pendente',
        ]);

        return redirect()->back()->with('success', 'Solicitação de adoção enviada com sucesso!');
    }

    /**
     * Lista as solicitações feitas pelo usuário (Adotante vê seus pedidos).
     */
    public function meusPedidos()
    {
        $requests = AdoptionRequest::where('user_id', Auth::id())
            ->with('pet')
            ->latest()
            ->paginate(10); // Melhoria: Paginação

        return view('adoptions.meus-pedidos', compact('requests'));
    }

    /**
     * Aprova uma solicitação.
     */
   public function approve(AdoptionRequest $adoptionRequest)
    {
        // Chama a Policy. Se falhar, o Laravel lança um erro 403 automaticamente.
        $this->authorize('update', $adoptionRequest);

        $adoptionRequest->update(['status' => 'aprovado']);

        // Rejeita outros interessados no mesmo pet
        AdoptionRequest::where('pet_id', $adoptionRequest->pet_id)
            ->where('id', '!=', $adoptionRequest->id)
            ->where('status', 'pendente')
            ->update(['status' => 'rejeitado']);

        return redirect()->back()->with('success', 'Adoção aprovada com sucesso!');
    }

    /**
     * Rejeita uma solicitação de adoção.
     */
    public function reject(AdoptionRequest $adoptionRequest)
    {
        $this->authorize('update', $adoptionRequest);

        $adoptionRequest->update(['status' => 'rejeitado']);

        return redirect()->back()->with('success', 'Solicitação rejeitada.');
    }
}