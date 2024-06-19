<?php 
    class Router{
        protected $routes = [];
        public function add($method, $uri,$controller){
            $this->routes[] =[
                'uri'=>$uri,
                'controller'=>$controller,
                'method'=>$method
            ];
        }

        public function get($uri, $controller){
            $this->add('GET',$uri,$controller);
        }
        public function post($uri, $controller){
            $this->add('POST',$uri,$controller);
        }
        public function delete($uri, $controller){
            $this->add('DELETE',$uri,$controller);
        }
        public function patch($uri, $controller){
            $this->add('PATCH',$uri,$controller);
        }
        public function put($uri, $controller){
            $this->add('PUT',$uri,$controller);
        }

        public function route($uri, $method){
            foreach($this->routes as $route) {
                if( $route['uri'] === $uri && $route['method'] === strtoupper($method)) {
                    require_once base_path($route['controller']);
                    return;
                }
            }
            $this->abort();
        }
        
        protected function abort($code=404){
            http_response_code($code);
            require_once base_path("views/{$code}.php");
            die();
        }
      
    }

    // require_once "util/functions.php";

    // $url = parse_url($_SERVER['REQUEST_URI'])['path'];
    

    // $routes = require('routes.php');
    // if (array_key_exists($url, $routes)){
    //     require_once $routes[$url];
    // }
    // else {
    //     http_response_code(404);
    //     require_once "views/404.php";
    // }

    // dumpAndDie($_SERVER);

