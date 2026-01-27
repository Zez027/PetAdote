<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pet;
use App\Models\AdoptionRequest;

class AdoptionController extends Controller
{
    public function store($petId)
    {
        AdoptionRequest::create([
            'user_id' => auth()->id(),
            'pet_id' => $petId,
            'status' => 'pendente'
        ]);

        return back()->with('success', 'Pedido de adoção enviado com sucesso!');
    }

    public function index()
    {
        // Busca todos os pedidos onde o pet pertence ao usuário logado
        $requests = AdoptionRequest::whereHas('pet', function($query) {
            $query->where('user_id', auth()->id());
        })
        ->with(['pet', 'user']) // Carrega dados do pet e de quem quer adotar
        ->latest()
        ->get();

        return view('adoptions.index', compact('requests'));
    }

    // 2. APROVAR PEDIDO
    public function approve($id)
    {
        $request = AdoptionRequest::findOrFail($id);
        
        // Verifica se o usuário é realmente dono do pet
        if ($request->pet->user_id !== auth()->id()) {
            abort(403);
        }

        $request->update(['status' => 'aprovado']);
        
        // Opcional: Atualizar status do pet para "em processo" ou "adotado"
        // $request->pet->update(['status' => 'adotado']); 

        return back()->with('success', 'Adoção aprovada! Entre em contato com o adotante.');
    }

    // 3. REJEITAR PEDIDO
    public function reject($id)
    {
        $request = AdoptionRequest::findOrFail($id);
        
        if ($request->pet->user_id !== auth()->id()) {
            abort(403);
        }

        $request->update(['status' => 'rejeitado']);

        return back()->with('success', 'Solicitação rejeitada.');
    }
}
