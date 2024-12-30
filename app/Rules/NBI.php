<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class NBI implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!preg_match('/^\d{9}[a-zA-Z]{2}\d{3}$/', $value)) {
            $fail('O campo :attribute deve ter 14 caracteres e seguir
             o formato correto de número de bilhete de identidade.');
        }
    }
}