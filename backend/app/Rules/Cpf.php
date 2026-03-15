<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class Cpf implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param Closure(string, ?string=): PotentiallyTranslatedString $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $cpf = preg_replace('/[^0-9]/is', '', $value);

        if (strlen($cpf) != 11) {
            $fail(__("cpf.invalid"));
            return;
        }

        if (preg_match('/(\d)\1{10}/', $cpf)) {
            $fail(__("cpf.invalid"));
            return;
        }

        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;

            if ((int)$cpf[$t] !== $d) {
                $fail(__('cpf.invalid'));
                return;
            }
        }
    }
}
