<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pet;

class AdminPetController extends Controller
{
    public function index(Request $request)
    {
        $query = Pet::with('user')->withTrashed();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nome', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('tipo')) {
            $query->where('tipo', $request->species);
        }

        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->whereNull('deleted_at');
            } elseif ($request->status === 'inactive') {
                $query->whereNotNull('deleted_at'); 
            }
        }

        $pets = $query->latest()->paginate(15)->withQueryString();
        
        return view('admin.pets.index', compact('pets'));
    }

    public function show($id)
    {
        $pet = Pet::with(['user', 'photos'])->withTrashed()->findOrFail($id);
        
        return view('admin.pets.show', compact('pet'));
    }

    public function toggleStatus($id)
    {
        $pet = Pet::withTrashed()->findOrFail($id);

        if ($pet->trashed()) {
            $pet->restore();
            $mensagem = 'Anúncio do pet reativado com sucesso.';
        } else {
            $pet->delete();
            $mensagem = 'Anúncio do pet inativado com sucesso.';
        }

        return back()->with('success', $mensagem);
    }
}
