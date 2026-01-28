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
        
        // Segurança: Apenas o dono do pet pode aprovar
        if ($request->pet->user_id !== auth()->id()) {
            abort(403);
        }
    
        // Usamos uma Transaction para garantir que todas as mudanças ocorram juntas
        \DB::transaction(function () use ($request) {
            // 1. Aprova esta solicitação
            $request->update(['status' => 'aprovado']);
    
            // 2. Marca o Pet como Adotado (ele sumirá da Home se você filtrar por status 'disponivel')
            $request->pet->update(['status' => 'adotado']);
    
            // 3. Opcional: Rejeita automaticamente as outras solicitações pendentes para este mesmo pet
            AdoptionRequest::where('pet_id', $request->pet_id)
                ->where('id', '!=', $request->id)
                ->where('status', 'pendente')
                ->update(['status' => 'rejeitado']);
        });
    
        return back()->with('success', 'Adoção aprovada! O pet foi marcado como adotado e o adotante já pode ver o seu contacto.');
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

    public function meusPedidos()
    {
        $pedidos = AdoptionRequest::where('user_id', auth()->id())
                    ->with('pet.photos')
                    ->latest()
                    ->get();

        return view('adoptions.meus-pedidos', compact('pedidos'));
    }
}
