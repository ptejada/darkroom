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
        if (function_exists('random_bytes')) {
            $str = '';
            while (($len = strlen($str)) < $length) {
                $size  = $length - $len;
                $bytes = random_bytes($size);
                $str   .= substr(str_replace(['/', '+', '='], '', base64_encode($bytes)), 0, $size);
            }
            return $str;
        }

        // Fallback logic to generate random string
        $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        return substr(str_shuffle(str_repeat($pool, ceil($length / strlen($pool)))), 1, $length);
    }

    /**
     * Generates a random file name
     *
     * @param string $pattern The name patten.
     *
     * @return null|string|string[]
     */
    public static function name($pattern = '%6-%4-%7')
    {
        return preg_replace_callback('/([a-zA-Z])|%(\d+)/', function ($param) {
            return count($param) === 3 ? self::random($param[2]) : date($param[1]);
        }, $pattern
        );
    }
}
