<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AdoptionRequest;

class AdminAdoptionController extends Controller
{
    public function index(Request $request)
    {
        $query = AdoptionRequest::with(['user', 'pet.user']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('pet', function($petQuery) use ($search) {
                      $petQuery->where('nome', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $adocoes = $query->latest()->paginate(15)->withQueryString();
        
        return view('admin.adoptions.index', compact('adocoes'));
    }

    public function show($id)
    {
        $adocao = AdoptionRequest::with([
            'user', 
            'pet' => function($query) { 
                $query->withTrashed(); 
            },
            'pet.user'
        ])->findOrFail($id);
        
        return view('admin.adoptions.show', compact('adocao'));
    }
}