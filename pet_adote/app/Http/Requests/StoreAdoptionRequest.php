<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAdoptionRequest extends FormRequest
{
    /**
     * Permite que qualquer usuário autenticado faça a requisição.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Regras de validação para a criação de uma adoção.
     */
    public function rules(): array
    {
        return [
            'pet_id' => 'required|exists:pets,id',
        ];
    }

    /**
     * Mensagens de erro personalizadas.
     */
    public function messages(): array
    {
        return [
            'pet_id.required' => 'O campo pet é obrigatório.',
            'pet_id.exists'   => 'O pet selecionado não foi encontrado.',
        ];
    }
}