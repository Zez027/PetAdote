<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use App\Rules\Cpf;

class AuthController extends Controller
{
    // --- LOGIN ---
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended(route('home'));
        }

        return back()->withErrors([
            'email' => 'As credenciais fornecidas estão incorretas.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    // --- CADASTRO (REGISTER) ---
    public function register(Request $request)
    {
        // 1. Limpeza: Remove tudo que não é número do CPF e Contato antes de validar
        $request->merge([
            'cpf' => preg_replace('/\D/', '', $request->cpf),
            'contato' => preg_replace('/\D/', '', $request->contato),
        ]);

        // 2. Mensagens personalizadas em Português
        $messages = [
            'required' => 'O campo :attribute é obrigatório.',
            'email' => 'O e-mail deve ser um endereço válido.',
            'unique' => 'Este :attribute já está cadastrado.',
            'cpf' => 'CPF invalido ou cadastrado no sistema',
            'confirmed' => 'A confirmação da senha não confere.',
            'min' => 'O campo :attribute deve ter pelo menos :min caracteres.',
            'url' => 'O link do :attribute deve ser um endereço web válido.',
            'password.min' => 'A senha deve ter mais de 8 caracteres.',
            'password.mixed' => 'A senha deve conter letras maiúsculas e minúsculas.',
            'password.symbols' => 'A senha deve conter pelo menos um caractere especial (!, @, #, etc).',
        ];

        // Nomes amigáveis para os erros
        $attributes = [
            'name' => 'nome',
            'password' => 'senha',
            'contato' => 'whatsapp',
            'profile_photo' => 'foto de perfil'
        ];

        // 3. Validação robusta
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'cpf' => ['required', 'string', 'size:11', new \App\Rules\Cpf, 'unique:users'],
            'password' => [
                'required',
                'confirmed',
                \Illuminate\Validation\Rules\Password::min(9)->mixedCase()->symbols()
            ],
            'contato' => 'required|string|max:20',
            'facebook' => 'nullable|url|max:255',
            'instagram' => 'nullable|url|max:255',
            'pais' => 'required|string|max:100',
            'estado' => 'required|string|max:100',
            'cidade' => 'required|string|max:100',
            'profile_photo' => 'nullable|image|max:2048'
        ], $messages, $attributes);

        // 4. Preparação e salvamento
        $data['password'] = \Illuminate\Support\Facades\Hash::make($data['password']);

        if ($request->hasFile('profile_photo')) {
            $data['profile_photo'] = $request->file('profile_photo')->store('profile_photos', 'public');
        }

        $user = \App\Models\User::create($data);

        \Illuminate\Support\Facades\Auth::login($user);

        return redirect()->route('home')->with('success', 'Bem-vindo! Sua conta foi criada com sucesso.');
    }

    // --- PERFIL ---
    // ... dentro da classe AuthController ...

    /**
     * Exibe o formulário de edição de perfil
     */
    public function edit()
    {
        $user = auth()->user(); // Pega o usuário logado
        return view('auth.edit', compact('user'));
    }

    /**
     * Processa a atualização dos dados do perfil
     */
    public function update(Request $request)
    {
        $user = auth()->user();

        // 1. Limpeza visual antes da validação (IGUAL AO REGISTER)
        $request->merge([
            'cpf' => preg_replace('/\D/', '', $request->cpf),
            'contato' => preg_replace('/\D/', '', $request->contato),
        ]);

        // 2. Validação
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'cpf' => ['required', 'string', 'size:11', new \App\Rules\Cpf, 'unique:users,cpf,' . $user->id],
            'contato' => 'required|string|max:20',
            'facebook' => 'nullable|url|max:255',
            'instagram' => 'nullable|url|max:255',
            'pais' => 'required|string|max:100',
            'estado' => 'required|string|max:100',
            'cidade' => 'required|string|max:100',
            'profile_photo' => 'nullable|image|max:2048'
        ]);

        // 3. Upload da Foto (se houver)
        if ($request->hasFile('profile_photo')) {
            if ($user->profile_photo) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($user->profile_photo);
            }
            $data['profile_photo'] = $request->file('profile_photo')->store('profile_photos', 'public');
        }

        // 4. Salva as alterações
        $user->update($data);

        return redirect()->route('perfil.edit')->with('success', 'Perfil atualizado com sucesso!');
    }
}