<?php

namespace APP\Classes;

class Log
{
    public static function saveLog($name, $text, $folder_name = '')
    {
        self::createLogsFolder($folder_name);

        $iteration = 1;
        $path = APP_LOGS_PATH . '/' . $folder_name . '/' . $name . '.txt';
        while (file_exists($path)) {
            $name = $name . '-' . $iteration;
            $path = APP_LOGS_PATH . '/' . $folder_name . '/' . $name  . '.txt';
            $iteration++;
        }
        $f = fopen($path, 'a+');
        fwrite($f, $text);
        fclose($f);
    }

    public static function createLogsFolder($path = '')
    {
        Files::createDirectory(APP_LOGS_PATH);

        if ($path != '') {
            Files::createDirectory(APP_LOGS_PATH . '/' . $path);
        }

        // disable direct access
        if (file_exists(APP_LOGS_PATH . '/.htaccess')) {
            $f = fopen(APP_LOGS_PATH . '/.htaccess', 'a+');
            fwrite($f, 'deny from all');
            fclose($f);
        }
    }
}
