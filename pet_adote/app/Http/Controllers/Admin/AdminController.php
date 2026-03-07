<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Pet;
use App\Models\AdoptionRequest;
use Illuminate\Support\Facades\DB; 

class AdminController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
        $totalPets = Pet::count();
        $totalAdocoes = AdoptionRequest::count();
        $adocoesAprovadas = AdoptionRequest::where('status', 'aprovado')->count();

        $adocoesPorStatus = AdoptionRequest::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        $petsPorEspecie = Pet::select('tipo', DB::raw('count(*) as total'))
            ->groupBy('tipo')
            ->pluck('total', 'tipo')
            ->toArray();

        return view('admin.dashboard', compact(
            'totalUsers', 'totalPets', 'totalAdocoes', 'adocoesAprovadas', 
            'adocoesPorStatus', 'petsPorEspecie'
        ));
    }
}