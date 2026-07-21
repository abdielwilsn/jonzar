<?php

namespace App\Utils;

class RandomStringGenerator
{
    public static function generate($length)
    {
        $domain = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
        $generated_string = "";
        $len = strlen($domain);
        for ($i = 0; $i < $length; $i++) {
            $index = rand(0, $len - 1);
            $generated_string .= $domain[$index];
        }
        return $generated_string;
    }
}
