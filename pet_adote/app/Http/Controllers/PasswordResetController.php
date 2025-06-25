<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\PasswordReset;

class PasswordResetController extends Controller
{
    // Mostrar formulário para solicitar o email
    public function requestForm()
    {
        return view('auth.passwords.email');
    }

    // Enviar email com link para reset
    public function sendResetEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with('status', __($status))
            : back()->withErrors(['email' => __($status)]);
    }

    // Mostrar formulário para colocar nova senha
    public function resetForm($token)
    {
        return view('auth.passwords.reset', ['token' => $token]);
    }

    // Atualizar senha no banco
    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => [
                'required',
                'confirmed',
                'min:8',
                'regex:/[A-Z]/',      // Maiúscula
                'regex:/[a-z]/',      // Minúscula
                'regex:/[0-9]/',      // Número
                'regex:/[@$!%*#?&]/', // Caractere especial
            ],
        ], [
            'password.regex' => 'A senha deve conter pelo menos uma letra maiúscula, uma minúscula, um número e um caractere especial.'
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->password = Hash::make($password);
                $user->setRememberToken(Str::random(60));
                $user->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('success', 'Senha atualizada com sucesso! Faça login.')
            : back()->withErrors(['email' => [__($status)]]);
    }
}
