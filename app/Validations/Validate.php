<?php

namespace App\Validations;

use App\Utils\Numeric;

abstract class Validate
{
    protected static int $Size;

    public static function handle(string $doc): bool
    {
        $doc = Numeric::only($doc);

        if (static::checkSize($doc)) 
            return false;

        if(Numeric::areEquals($doc))
            return false;

        return static::isValid($doc);
    }

    private static function checkSize(string $doc):bool
    {
        return (bool) (strlen($doc) != static::$Size);
    }

    abstract public static function isValid(string $doc):bool;
}