<?php

namespace Darkroom\Utility;

/**
 * Class Str
 *
 * @package Darkroom\Utility
 */
class Str
{
    /**
     * Generates a random string.
     *
     * @param int $length The size of the random string.
     *
     * @return string The random string
     * @throws \Exception Exception if it was not possible to gather sufficient entropy.
     */
    public static function random($length = 16)
    {
        $str = '';
        while (($len = strlen($str)) < $length) {
            $size  = $length - $len;
            $bytes = random_bytes($size);
            $str   .= substr(str_replace(['/', '+', '='], '', base64_encode($bytes)), 0, $size);
        }
        return $str;
    }

    /**
     * Generates a random file name
     *
     * @param string $pattern The name patten.
     *
     * @return null|string|string[]
     */
    public static function name($pattern = '%6-%4-%6')
    {
        return preg_replace_callback(
            '/([a-zA-Z])|%(\d+)/',
            function ($param){
                return count($param) === 3 ? self::random($param[2]) : date($param[1]);
            },
            $pattern
        );
    }
}
