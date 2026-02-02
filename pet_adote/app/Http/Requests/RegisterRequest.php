<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;
use App\Rules\Cpf;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    protected function prepareForValidation()
    {
        // Limpeza de CPF e Contato antes da validação
        $this->merge([
            'cpf' => preg_replace('/\D/', '', $this->cpf),
            'contato' => preg_replace('/\D/', '', $this->contato),
        ]);
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'cpf' => ['required', 'string', 'size:11', new Cpf, 'unique:users'],
            'password' => [
                'required', 'confirmed',
                Password::min(8)->letters()->numbers()->symbols()->mixedCase(),
            ],
            'contato' => 'required|string|max:20',
            'facebook' => 'nullable|url|max:255',
            'instagram' => 'nullable|url|max:255',
            'pais' => 'required|string|max:100',
            'estado' => 'required|string|max:100',
            'cidade' => 'required|string|max:100',
            'profile_photo' => 'nullable|image|max:2048'
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'nome',
            'password' => 'senha',
            'contato' => 'whatsapp',
            'profile_photo' => 'foto de perfil'
        ];
    }

    public function messages()
    {
        return [
            'required' => 'O campo :attribute é obrigatório.',
            'unique' => 'Este :attribute já está em uso.',
            'confirmed' => 'As senhas não coincidem.',
            'password.min' => 'A senha deve ter pelo menos 8 caracteres.',
            // ... outras mensagens conforme seu código original
        ];
    }
}