<?php

namespace App\Utils;


class Numeric
{
    public static function isNumeric($value): bool
    {
        return self::only($value) == $value;
    }

    public static function only(string|int $str):string
    {
        return (string) preg_replace('/[^0-9]/is', '', $str);
    }

    public static function areEquals(string $str):bool
    {
        $size = strlen($str) -1;
        return (bool) preg_match('/(\d)\1{' . $size . '}/', $str);
    }
}