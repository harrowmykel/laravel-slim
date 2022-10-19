<?php

use APP\Router;

ini_set('display_errors', 1); 
ini_set('display_startup_errors', 1); 
error_reporting(E_ALL);

define("APP_IS_WEB", true);

require_once __DIR__.'/inc/inc.php';

Router::main();