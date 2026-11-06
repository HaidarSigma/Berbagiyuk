<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class PasswordKuat implements ValidationRule
{
    public const MIN = 12;

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $kurang = [];

        if (mb_strlen($value) < self::MIN)         $kurang[] = 'minimal '.self::MIN.' karakter';
        if (! preg_match('/\p{Lu}/u', $value))     $kurang[] = 'huruf besar';
        if (! preg_match('/\p{Ll}/u', $value))     $kurang[] = 'huruf kecil';
        if (! preg_match('/\d/', $value))          $kurang[] = 'angka';
        if (! preg_match('/[^\p{L}\d]/u', $value)) $kurang[] = 'simbol (contoh: ! @ # $ %)';

        if ($kurang) {
            $fail('Password belum memenuhi syarat. Kurang: '.implode(', ', $kurang).'.');
        }
    }
}