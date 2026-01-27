<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pet;

class FavoriteController extends Controller
{
   public function toggle($petId)
    {
        $user = auth()->user();
        $pet = Pet::findOrFail($petId);

        // Se já favoritou, desfavorita (toggle)
        $user->favorites()->toggle($petId);

        return back();
    }
}
