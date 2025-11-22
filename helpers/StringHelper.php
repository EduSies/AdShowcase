<?php

namespace app\helpers;

class StringHelper
{
    public static $mode = MB_CASE_TITLE;
    public static $encoding = 'UTF-8';

    public static function camelCase($data, $separator = ' ')
    {
        return str_replace($separator, '', ucwords($data, $separator));
    }

    public static function titleize($name)
    {
        $name = strtolower($name);
        $name = mb_convert_case($name, self::$mode, self::$encoding);
        return $name;
    }
}