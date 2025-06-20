<?php

namespace App\Http\Controllers;

use App\Models\Pet;

class HomeController extends Controller
{
    public function index()
    {
        $pets = Pet::with('photos', 'user')->latest()->get();
        return view('home', compact('pets'));
    }
}
