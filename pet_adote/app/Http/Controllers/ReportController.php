<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'reportable_id'   => 'required|integer',
            'reportable_type' => 'required|string',
            'reason'          => 'required|string|max:255',
            'description'     => 'nullable|string|max:1000',
        ]);

        if ($validated['reportable_type'] === 'App\Models\Pet') {
            $pet = \App\Models\Pet::findOrFail($validated['reportable_id']);
            if ($pet->user_id === Auth::id()) {
                return back()->with('error', 'Não pode denunciar o seu próprio anúncio.');
            }
        }

        Report::create([
            'user_id'         => Auth::id(),
            'reportable_id'   => $validated['reportable_id'],
            'reportable_type' => $validated['reportable_type'],
            'reason'          => $validated['reason'],
            'description'     => $validated['description'],
            'status'          => 'pendente',
        ]);

        return back()->with('success', 'Denúncia enviada com sucesso. A nossa equipa irá analisar a situação brevemente.');
    }
}