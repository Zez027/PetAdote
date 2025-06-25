<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    // Registro de usuário
    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => [
                'required',
                'string',
                'min:6',
                'confirmed',
                'regex:/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[^A-Za-z0-9]).+$/'
            ],
            'cpf' => 'required|string|max:14|unique:users,cpf',
            'pais' => 'required|string|max:100',
            'estado' => 'required|string|max:100',
            'cidade' => 'required|string|max:100',
            'facebook' => 'nullable|url',
            'instagram' => 'nullable|url',
            'contato' => 'nullable|string|max:20',
            'role' => 'nullable|in:user,admin',
        ], [
            'password.regex' => 'A senha deve conter pelo menos uma letra maiúscula, um número e um caractere especial.',
        ]);

        $data['password'] = Hash::make($data['password']);
        $data['role'] = $data['role'] ?? 'user';

        User::create($data);

        return redirect()->route('login')->with('success', 'Cadastro realizado com sucesso! Faça login.');
    }

    // Exibir form para editar perfil
    public function edit()
    {
        $user = auth()->user();
        return view('auth.edit', compact('user'));
    }

    // Atualizar perfil (sem alterar senha)
    public function update(Request $request)
    {
        $user = auth()->user();

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'cpf' => 'required|string|max:14|unique:users,cpf,' . $user->id,
            'contato' => 'nullable|string|max:20',
            'pais' => 'required|string|max:100',
            'estado' => 'required|string|max:100',
            'cidade' => 'required|string|max:100',
            'facebook' => 'nullable|url',
            'instagram' => 'nullable|url',
        ]);

        $user->update($data);

        return redirect()->route('perfil.edit')->with('success', 'Dados atualizados com sucesso!');
    }

    // Login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (!auth()->attempt($credentials)) {
            throw ValidationException::withMessages([
                'email' => ['Credenciais incorretas.'],
            ]);
        }

        $request->session()->regenerate();

        return redirect()->route('home')->with('success', 'Login efetuado com sucesso!');
    }

    // Logout
    public function logout(Request $request)
    {
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Logout realizado com sucesso!');
    }
}
