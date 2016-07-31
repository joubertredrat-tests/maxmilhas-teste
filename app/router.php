<?php

namespace App;

class Router
{
    public static function getUri()
    {
        
    }

    public static function run()
    {
        $route = isset($_SERVER['QUERY_STRING']) ? substr($_SERVER['QUERY_STRING'], 1) : 'index';

        $uri = explode('/', $route);

        switch (count($uri)) {
            case 1:
                echo 'one';
                $class = $uri[0];
                $method = 'index';
                $args = null;
                break;
            case 2:
                $class = $uri[0];
                $method = $uri[1];
                $args = null;
                break;
            case 3:
            case 4:
            case 5:
                $class = $uri[0];
                $method = $uri[1];
                unset($uri[0]);
                unset($uri[1]);
                $args = $uri;
                break;
            default:
                exit('Unknown route');
                break;
        }


        var_dump($class, $method, $args);

        exit();

        //require(CONTROLLER_PATH.DIRECTORY_SEPARATOR.$class.'.php');
        $class = ucfirst($class);

        $Controller = new $class();
        $Controller->$method();

        echo $class;
    }
}