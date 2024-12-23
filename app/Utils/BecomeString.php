<?php

namespace App\Utils;

class BecomeString
{

    public static function ByArray(array $array)
    {
        $string = '';
        foreach($array as $c => $v){
            $string .= is_array($v)
                ? self::recursiveFor($v, $c)
                : self::makeText($c, $v);
        }

        return $string;
    }

    private static function recursiveFor(array $array, string $prefix)
    {
        $string = '';
        foreach($array as $c => $v){
            $string .= is_array($v)
                ? self::recursiveFor($v, "$prefix.$c")
                : self::makeText("$prefix.$c", $v);
        }

        return $string;
    }

    private static function makeText($c, $v, $prefix = '')
    {
        return "$c => $v" . PHP_EOL;
    }

    public static function test()
    {
        $array = [
            'nome' => 'Jo',
            'idade' => 36,
            'pet' => [
                'raça' => 'Gato',
                'Nome' => 'Sid'
            ]
        ];
        return self::ByArray($array);
    }

}