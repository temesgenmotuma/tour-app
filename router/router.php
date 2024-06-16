<?php 

    require_once "util/functions.php";

    $url = parse_url($_SERVER['REQUEST_URI'])['path'];
    

    $routes = require('routes.php');
    if (array_key_exists($url, $routes)){
        require_once $routes[$url];
    }
    else {
        http_response_code(404);
        require_once "views/404.php";
    }

    dumpAndDie($_SERVER);

