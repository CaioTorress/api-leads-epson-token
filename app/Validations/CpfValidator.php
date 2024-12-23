<?php

namespace App\Validations;

class CpfValidator extends Validate
{
    protected static int $Size = 11;

    public static function isValid(string $cpf):bool
    {
        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf[$c] != $d) {
                return false;
            }
        }

        return true;
    }


    public static function setMask(string $cpf)
    {
        return sprintf(
            '%d%d%d.%d%d%d.%d%d%d-%d%d',
            ...str_split($cpf)
        );
    }
}