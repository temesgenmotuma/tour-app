<?php 

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    
    
    const BASE_PATH = __DIR__ . DIRECTORY_SEPARATOR;
    // var_dump(__DIR__);
    // var_dump(BASE_PATH);
    

    //import functions.php
    require_once BASE_PATH."util".DIRECTORY_SEPARATOR."functions.php";
    
    //import router
    require_once base_path("router".DIRECTORY_SEPARATOR."router.php");

    //instantiate Router
    $router = new Router();
    
    //import routes
    //routes registers all the listener to differenct routes(urls and http methods)
    require_once base_path('router'.DIRECTORY_SEPARATOR.'routes.php');
    
    // dumpAndDie($router);
    
    //get the uri
    $uri = parse_url($_SERVER['REQUEST_URI'])['path'];
    

    //get method from form
    $method = $_POST['_method'] ?? $_SERVER['REQUEST_METHOD'];    
    

    //route to the given uri and method
    $router->route($uri,$method);
    