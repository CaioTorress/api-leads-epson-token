<?php

namespace App\Services;

use App\Models\Participant;

class GenerateLuckyNumberService
{
    /**
     * Gera um número único de sorte com a quantidade de dígitos especificada.
     *
     * @param int $digits Quantidade de dígitos do número.
     * @return string Lucky number único.
     * @throws \InvalidArgumentException Se o número de dígitos for menor que 1.
     */
    public function generateUniqueCode(int $digits): string
    {
        if ($digits < 1) {
            throw new \InvalidArgumentException('A quantidade de dígitos deve ser maior ou igual a 1.');
        }

        do {
            $min = (int) str_repeat('1', $digits);
            $max = (int) str_repeat('9', $digits);
            $randomNumber = (string) random_int($min, $max);
        } while (Participant::where('lucky_number', $randomNumber)->exists());

        return $randomNumber;
    }
}
