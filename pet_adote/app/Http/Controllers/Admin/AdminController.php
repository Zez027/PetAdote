<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Pet;
use App\Models\AdoptionRequest;

class AdminController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
        $totalPets = Pet::count();
        
        $totalAdocoes = AdoptionRequest::where('status', 'aprovado')->count();

        return view('admin.dashboard', compact('totalUsers', 'totalPets', 'totalAdocoes'));
    }
}
