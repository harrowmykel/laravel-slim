<?php

use APP\Config;

define("DIR_SEP", DIRECTORY_SEPARATOR);
define("APP_CURRENT_TIME", time());

define("ROOT_DIR", __DIR__ . "/..");
define("APP_READY", true);
session_start();

// require_once ROOT_DIR . '/vendor/autoload.php';

/**
 * this imports all php files in a folder and it's subfolders
 * by default it excludes the index.php
 */
function import_all_php_files_in_folder($folder_path, $exclude_index_files = true)
{

    if (!is_dir($folder_path)) {
        return false;
    }

    $data_in_folder = scandir($folder_path);

    foreach ($data_in_folder as $d_name) {
        $full_path_to_data = $folder_path . DIR_SEP . $d_name;

        if ($d_name == '.' || $d_name == "..") {
            continue;
        }

        //don't include yourself
        if ($full_path_to_data == __FILE__) {
            continue;
        }
        //is directory
        if (is_dir($full_path_to_data)) {

                        import_all_php_files_in_folder($full_path_to_data, $exclude_index_files);
        } else if (str_ends_with($d_name, ".php")) {
            //is php file
            // is index file
            if (str_starts_with($d_name, 'index') && $exclude_index_files) {
                continue;
            }
            require_once $full_path_to_data;
        }
    }

    return true;
}


/**
 * returns the value of a server key
 * this is useful if php is run in command where certain 
 * keys are absent like in commmand line
 */
function server_value($name, $default = '')
{
    return (isset($_SERVER[$name])) ? trim($_SERVER[$name]) : $default;
}

if(!function_exists('str_ends_with')){
    function str_ends_with($haysack, $needle){
        $l_needle = strlen($needle);
        $l_haysack = strlen($haysack);
        if($l_needle > $l_haysack){
            return false;
        }
        return substr($haysack, -$l_needle) === $needle;
    }
}
if(!function_exists('str_starts_with')){
    function str_starts_with($haysack, $needle){
        $l_needle = strlen($needle);
        $l_haysack = strlen($haysack);
        if($l_needle > $l_haysack){
            return false;
        }
        return substr($haysack, 0, $l_needle) === $needle;
    }
}

import_all_php_files_in_folder(ROOT_DIR . DIR_SEP . 'inc');
import_all_php_files_in_folder(ROOT_DIR . DIR_SEP . 'classes');
import_all_php_files_in_folder(ROOT_DIR . DIR_SEP . 'app-api-functions');
import_all_php_files_in_folder(ROOT_DIR . DIR_SEP . 'models');
import_all_php_files_in_folder(ROOT_DIR . DIR_SEP . 'templates');

//set up the congiuration
// Config::i();
