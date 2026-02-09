<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pet;

class HomeController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('auth');
    }

    
    public function index(Request $request)
    {

        $query = Pet::query();

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

        $pets = $query->latest()->paginate(9)->withQueryString();

        $cidades = Pet::select('cidade')
                    ->whereNotNull('cidade')
                    ->distinct()
                    ->orderBy('cidade')
                    ->pluck('cidade');

        return view('home', compact('pets', 'cidades'));
    }
}