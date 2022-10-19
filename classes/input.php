<?php

namespace APP\Classes;

class Input
{

    //returns http get value
    public static function get($name, $default = null)
    {
        return isset($_GET[$name]) ? $_GET[$name] : $default;
    }


    //returns http post value
    public static function post($name, $default = null)
    {
        return isset($_POST[$name]) ? $_POST[$name] : $default;
    }
}
