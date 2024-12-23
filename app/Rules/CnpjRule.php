<?php

namespace App\Rules;


use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

use App\Validations\CnpjValidator;

class CnpjRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!CnpjValidator::handle($value))
            $fail('CPF inválido');
    }
}
