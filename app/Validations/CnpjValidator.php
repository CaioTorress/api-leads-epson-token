<?php

namespace App\Validations;

class CnpjValidator extends Validate
{
    protected static int $Size = 14;

    public static function isValid(string $cnpj): bool
    {
        // Valida primeiro dígito verificador
        for ($i = 0, $j = 5, $soma = 0; $i < 12; $i++) {
            $soma += $cnpj[$i] * $j;
            $j = ($j == 2) ? 9 : $j - 1;
        }

        $resto = $soma % 11;

        if ($cnpj[12] != ($resto < 2 ? 0 : 11 - $resto))
            return false;

        // Valida segundo dígito verificador
        for ($i = 0, $j = 6, $soma = 0; $i < 13; $i++) {
            $soma += $cnpj[$i] * $j;
            $j = ($j == 2) ? 9 : $j - 1;
        }

        $resto = $soma % 11;

        return $cnpj[13] == ($resto < 2 ? 0 : 11 - $resto);
    }

    public static function setMask(string $cnpj)
    {
        return sprintf(
            '%d%d.%d%d%d.%d%d%d/%d%d%d-%d%d',
            ...str_split($cnpj)
        );
    }
}
