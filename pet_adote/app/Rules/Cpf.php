<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class Cpf implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Remove caracteres não numéricos
        $c = preg_replace('/\D/', '', $value);

        // Verifica se tem 11 dígitos ou se todos são iguais
        if (strlen($c) != 11 || preg_match("/^{$c[0]}{11}$/", $c)) {
            $fail('O CPF informado é inválido.');
            return;
        }

        // Validação do 1º dígito verificador
        for ($s = 10, $n = 0, $i = 0; $s >= 2; $n += $c[$i++] * $s--);
        if ($c[9] != ((($n %= 11) < 2) ? 0 : 11 - $n)) {
            $fail('O CPF informado é inválido.');
            return;
        }

        // Validação do 2º dígito verificador
        for ($s = 11, $n = 0, $i = 0; $s >= 2; $n += $c[$i++] * $s--);
        if ($c[10] != ((($n %= 11) < 2) ? 0 : 11 - $n)) {
            $fail('O CPF informado é inválido.');
        }
    }
}