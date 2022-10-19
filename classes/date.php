<?php

namespace APP\Classes;

class ODate
{

    //returns human readable e.g 31-10-2022 10:45:23
    public static function human($timestamp = -1)
    {
        if ($timestamp == -1) {
            $timestamp = time();
        }
        return date('d-m-Y H:i:s', $timestamp);
    }

    public static function fileFormat($timestamp = -1)
    {
        if ($timestamp == -1) {
            $timestamp = time();
        }
        return date('d-m-Y-H-i-s', $timestamp);
    }

    public static function fileFormatDateOnly($timestamp = -1)
    {
        if ($timestamp == -1) {
            $timestamp = time();
        }
        return date('d-m-Y', $timestamp);
    }
}
