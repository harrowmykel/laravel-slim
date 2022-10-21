<?php

namespace APP;

defined('APP_READY') || die();



class Config
{

    //instance fof the Config class
    protected static $instance;

    public static function i(): Config
    {
        if (self::$instance == null) {
            self::$instance = new Config();
            self::$instance->init();
        }
        return self::$instance;
    }


    //define constants here
    public function init()
    {
        //this should be the link to your website 
        define('ENVIRONMENT_URL', 'https://example.com');
        define('APP_LOGS_PATH', ROOT_DIR . "/logs");
    }
}
