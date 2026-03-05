<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class AdminUserController extends Controller
{
    public function index()
    {
        $users = User::latest()->paginate(15);
        
        return view('admin.users.index', compact('users'));
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        
        return view('admin.users.show', compact('user'));
    }

    public function toggleSuspension($id)
    {
        $user = User::findOrFail($id);
        
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Você não pode suspender sua própria conta.');
        }

        $user->is_suspended = !$user->is_suspended;
        $user->save();

        $mensagem = $user->is_suspended ? 'Usuário suspenso com sucesso.' : 'Conta do usuário reativada.';
        
        return back()->with('success', $mensagem);
    }
}