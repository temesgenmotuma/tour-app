<?php
    
    function dumpAndDie($value){
        echo "<pre>";
        var_dump($value);
        echo "</pre>";
        die();
    }

    function base_path($path) {
        return BASE_PATH . $path;
    }

    //render a specific view
    function render($path, $attributes =[]) {
        
        // extract($attributes);
        require base_path('views/'. $path);
        
    }

    // dumpAndDie(base_path('router\routes.php'));



