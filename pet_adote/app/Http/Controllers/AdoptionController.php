<?php

namespace App\Http\Controllers;

use App\Models\AdoptionRequest;
use App\Models\Pet;
use App\Http\Requests\StoreAdoptionRequest; // Importando o novo Request
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\NewAdoptionRequestNotification;
use App\Notifications\AdoptionStatusUpdatedNotification;
use Barryvdh\DomPDF\Facade\Pdf;

class AdoptionController extends Controller
{
    /**
     * Lista solicitações recebidas (Doador vê quem quer adotar seus pets).
     */
    public function index()
    {
        $baseQuery = AdoptionRequest::whereHas('pet', function ($query) {
            $query->withTrashed()->where('user_id', Auth::id());
        })->with(['pet', 'pet.photos', 'user'])->latest();

        // consultas por status
        $pendentes = (clone $baseQuery)->whereIn('status', ['pendente', 'em_analise'])->get();
        $aprovados = (clone $baseQuery)->where('status', 'aprovado')->get();
        $rejeitados = (clone $baseQuery)->where('status', 'rejeitado')->get();

        $totalRequests = $pendentes->count() + $aprovados->count() + $rejeitados->count();

        return view('adoptions.index', compact('pendentes', 'aprovados', 'rejeitados', 'totalRequests'));
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

        $pet->user->notify(new NewAdoptionRequestNotification($adoptionRequest));

        return redirect()->back()->with('success', 'Solicitação de adoção enviada com sucesso!');
    }

    /**
     * Lista as solicitações feitas pelo usuário (Adotante vê seus pedidos).
     */
    public function meusPedidos()
    {
        $requests = AdoptionRequest::where('user_id', Auth::id())
            ->with(['pet.photos', 'statusLogs'])
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
    public function reject(Request $request, $id)
    {
        // Valida se a pessoa digitou um motivo
        $request->validate([
            'motivo_rejeicao' => 'required|string|max:1000',
        ], [
            'motivo_rejeicao.required' => 'Por favor, informe um motivo para a rejeição.'
        ]);

        $adoptionRequest = AdoptionRequest::findOrFail($id);

        // Bloqueio de segurança: só o dono do pet pode rejeitar
        if ($adoptionRequest->pet->user_id !== auth()->id()) {
            abort(403, 'Acesso negado.');
        }

        $adoptionRequest->update([
            'status' => 'rejeitado',
            'motivo_rejeicao' => $request->motivo_rejeicao
        ]);

        return back()->with('success', 'Solicitação rejeitada. A mensagem foi enviada ao adotante!');
    }

    public function updateStatus(Request $request, $id)
    {
        $adoptionRequest = AdoptionRequest::findOrFail($id);

        // Validação de segurança: apenas o dono do Pet pode mudar o status
        if ($adoptionRequest->pet->user_id !== Auth::id()) {
            abort(403, 'Ação não autorizada.');
        }

        $request->validate([
            'status' => 'required|in:em_analise,aprovado,rejeitado',
            'motivo_rejeicao' => 'nullable|string|max:255'
        ]);

        $adoptionRequest->status = $request->status;
        
        if ($request->status === 'rejeitado') {
            $adoptionRequest->motivo_rejeicao = $request->motivo_rejeicao;
        }
        elseif ($request->status === 'aprovado') {
            AdoptionRequest::where('pet_id', $adoptionRequest->pet_id)
                ->where('id', '!=', $adoptionRequest->id)
                ->where('status', 'pendente')
                ->update(['status' => 'rejeitado']);
             
            // Pet não aparecerá mais para adoção pública, mas os dados permanecem salvos.
            $adoptionRequest->pet->delete();
        }

        $adoptionRequest->save();

        $adoptionRequest->user->notify(new AdoptionStatusUpdatedNotification($adoptionRequest));

        return redirect()->back()->with('success', 'Status da solicitação atualizado com sucesso!');
    }

    /**
     * Gera o PDF do Termo de Adoção Responsável
     */
    public function downloadContract($id)
    {
        $adoptionRequest = AdoptionRequest::with(['user', 'pet.user'])->findOrFail($id);

        // Segurança: Só o doador ou o adotante daquele pedido podem baixar o contrato
        if (Auth::id() !== $adoptionRequest->user_id && Auth::id() !== $adoptionRequest->pet->user_id) {
            abort(403, 'Acesso não autorizado.');
        }

        if ($adoptionRequest->status !== 'aprovado') {
            abort(400, 'O contrato só está disponível após a aprovação da adoção.');
        }

        // Gera o PDF a partir de uma view que vamos criar
        $pdf = Pdf::loadView('adoptions.contract', compact('adoptionRequest'));
        
        // Retorna o download do arquivo
        return $pdf->download('termo_adocao_' . strtolower(str_replace(' ', '_', $adoptionRequest->pet->nome)) . '.pdf');
    }
}