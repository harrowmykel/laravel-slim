<?php

namespace APP;

defined('APP_READY') || die();

class Router
{

    //all routes and the file to handle them
    protected static $routes = [
        //pages
        "index" => "client/index.php",
        // web_api/index
        "web_api_index" => "api/index.php"
    ];

    /**
     * it contains
     * ['link1', 'link2']
     * wenn a user visits
     * DOMAIN/link1/link2
     */
    protected static $url_params = [];

    /**
     * show a view based on the browser url
     */
    public static function main()
    {
        $_current_link = $_SERVER['REQUEST_URI'];
        $real_link = $_current_link;
        $real_link = (explode('?', $real_link))[0];
        $real_link = (explode('#', $real_link))[0];

        static::$url_params = [];

        $url_param_1 = explode('/', $real_link);
        foreach ($url_param_1 as $url_param) {
            $url_param = trim($url_param);
            if (empty($url_param)) {
                continue;
            }
            static::$url_params[] = $url_param;
        }

        $path = self::getUrlParamInPosition(0, "index");
        $path_0 = self::getUrlParamInPosition(0, "index");
        $path_1 = self::getUrlParamInPosition(1, "index");
        $path_2 = self::getUrlParamInPosition(2, "index");
        $view_path = "";

        //find the route to show
        foreach (self::$routes as $path_name => $route) {
            if ($path_name == $path) {
                $view_path = $route;
            }
        }

        if ($path == "web-api") {
            //
            $origin = server_value('HTTP_ORIGIN');
            $allowed = str_contains($origin, 'example.com');

            if($allowed){
                // Cross-Origin Resource Sharing Header
                header('Access-Control-Allow-Origin: '.$origin);
                header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
                header('Access-Control-Allow-Headers: X-Requested-With, Content-Type, Accept');
            }

            $path = 'web_api_' . self::getUrlParamInPosition(1, "index");
            if (empty($path) || $path == '') {
                $path = 'web_api_index';
            }
        }

        //open the request page in pages
        $fullpath = ROOT_DIR . DIR_SEP . "pages" . DIR_SEP . $view_path;
        //if not found
        if (is_dir($fullpath) || !file_exists($fullpath)) {
            $path = "404";
            foreach (self::$routes as $path_name => $route) {
                if ($path_name == $path) {
                    $view_path = $route;
                }
            }
        }

        //for app apis, use json
        if ($path == "404" && $path_0 == 'app-api') {
            release([
                "error" => 404,
                "error_text" => "no such file"
            ]);
        }
        if ($path == "404") {
            http_response_code(404);
            if (str_ends_with($real_link, '.js') || str_ends_with($real_link, '.css')) {
            }
        }
        //open the request page in pages
        $fullpath = ROOT_DIR . DIR_SEP . "pages" . DIR_SEP . $view_path;

        require_once $fullpath;
    }

    public static function getUrlParams()
    {
        return static::$url_params;
    }
    /**
     * get the paramter from link based on position
     */
    public static function getUrlParamInPosition($positon, $defaultVal = null)
    {
        if (isset(static::$url_params[$positon])) {
            return static::$url_params[$positon];
        }
        return $defaultVal;
    }

    /**
     * returns the current url and add
     * addition query to the get param
     */
    public static function currentUrl($additional_query = [])
    {
        $params = array_merge($_GET, $additional_query);
        return self::appendQueryStringToURL(ENVIRONMENT_URL . server_value('REQUEST_URI'), $params);
    }


    /**
     * @param string $url
     * @param $query string|array
     * @return string
     */
    public static function appendQueryStringToURL($url, $query)
    {
        // the query is empty, return the original url straightaway
        if (empty($query)) {
            return $url;
        }

        $parsedUrl = parse_url($url);
        if (empty($parsedUrl['path'])) {
            $url .= '/';
        }

        // if the query is array convert it to string
        $queryString = is_array($query) ? http_build_query($query) : $query;

        // check if there is already any query string in the URL
        if (empty($parsedUrl['query'])) {
            // remove duplications
            parse_str($queryString, $queryStringArray);
            $url .= '?' . http_build_query($queryStringArray);
        } else {
            $queryString = $parsedUrl['query'] . '&' . $queryString;

            // remove duplications
            parse_str($queryString, $queryStringArray);

            // place the updated query in the original query position
            $url = substr_replace($url, http_build_query($queryStringArray), strpos($url, $parsedUrl['query']), strlen($parsedUrl['query']));
        }

        return $url;
    }

    /**
     * creates a link to path and add
     * param to the get param
     */
    public static function linkToUrl($path, $params = [])
    {
        $url = self::appendQueryStringToURL(ENVIRONMENT_URL  . $path, $params);
        return $url;
    }

    public static function currentUrlWithoutGetParams()
    {
        return (explode('?', self::currentUrl()))[0];
    }

}
