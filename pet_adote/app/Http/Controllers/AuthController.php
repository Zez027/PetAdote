<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Events\Registered;
use Illuminate\Validation\Rules\Password;
use App\Rules\Cpf;

class AuthController extends Controller
{
    /**
     * LOGIN com Rate Limiting
     */
    public function login(Request $request) 
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $throttleKey = strtolower($request->input('email')) . '|' . $request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            throw ValidationException::withMessages([
                'email' => "Muitas tentativas. Tente novamente em $seconds segundos.",
            ]);
        }

        if (Auth::attempt($request->only('email', 'password'), $request->filled('remember'))) {
            RateLimiter::clear($throttleKey);
            $request->session()->regenerate();
            return redirect()->intended(route('home'));
        }

        RateLimiter::hit($throttleKey);

        return back()->withErrors([
            'email' => 'E-mail ou senha incorretos.',
        ])->onlyInput('email');

        return back()->withErrors(['email' => 'Credenciais inválidas.'])->onlyInput('email');
    }

    /**
     * CADASTRO com disparo de Verificação de E-mail
     */
    public function register(Request $request)
    {
        $request->merge([
            'cpf' => preg_replace('/\D/', '', $request->cpf),
            'contato' => preg_replace('/\D/', '', $request->contato),
        ]);

        $messages = [
            'required' => 'O campo :attribute é obrigatório.',
            'unique' => 'Este :attribute já está cadastrado.',
            'cpf' => 'CPF inválido ou já cadastrado.',
            'confirmed' => 'A confirmação da senha não confere.',
            'password.min' => 'A senha deve ter pelo menos 8 caracteres.',
        ];

        $attributes = [
            'name' => 'nome',
            'password' => 'senha',
            'contato' => 'whatsapp',
        ];

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'cpf' => ['required', 'string', 'size:11', new Cpf, 'unique:users'],
            'password' => [
                'required', 'confirmed',
                \Illuminate\Validation\Rules\Password::min(8)->letters()->numbers()->symbols(),
            ],
            'contato' => 'required|string|max:20',
            'pais' => 'required|string|max:100',
            'estado' => 'required|string|max:100',
            'cidade' => 'required|string|max:100',
            'profile_photo' => 'nullable|image|max:2048'
        ], $messages, $attributes);

        $data['password'] = Hash::make($data['password']);

        if ($request->hasFile('profile_photo')) {
            $data['profile_photo'] = $request->file('profile_photo')->store('profile_photos', 'public');
        }

        $user = User::create($data);

        // Gatilho para enviar o e-mail de verificação
        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('verification.notice');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    public function edit()
    {
        $user = auth()->user();
        return view('auth.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $request->merge([
            'cpf' => preg_replace('/\D/', '', $request->cpf),
            'contato' => preg_replace('/\D/', '', $request->contato),
        ]);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'cpf' => ['required', 'string', 'size:11', new Cpf, 'unique:users,cpf,' . $user->id],
            'contato' => 'required|string|max:20',
            'pais' => 'required|string|max:100',
            'estado' => 'required|string|max:100',
            'cidade' => 'required|string|max:100',
            'profile_photo' => 'nullable|image|max:2048'
        ]);

        if ($request->hasFile('profile_photo')) {
            if ($user->profile_photo) {
                Storage::disk('public')->delete($user->profile_photo);
            }
            $data['profile_photo'] = $request->file('profile_photo')->store('profile_photos', 'public');
        }

        $user->update($data);

        return redirect()->route('perfil.edit')->with('success', 'Perfil atualizado!');
    }

    /**
     * Exibe o formulário de alteração de senha
     */
    public function editPassword()
    {
        return view('auth.passwords.change');
    }

    /**
     * Processa a alteração de senha
     */
    public function updatePassword(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'current_password' => ['required', 'current_password'], // Valida se a senha atual está correta
            'password' => [
                'required', 
                'confirmed', 
                Password::min(8)->letters()->numbers()->symbols()->mixedCase()
            ],
        ], [
            'current_password' => 'A senha atual está incorreta.',
            'password.confirmed' => 'A confirmação da nova senha não confere.',
            'password.min' => 'A nova senha deve ter pelo menos 8 caracteres.',
        ]);

        // Atualiza a senha no banco (criptografada)
        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return redirect()->route('perfil.edit')->with('success', 'Senha alterada com sucesso!');
    }
}