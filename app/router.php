<?php
/**
 * Sistema de rotas da aplicação.
 *
 * @author Joubert <eu@redrat.com.br>
 */

namespace App;

class Router
{
    /**
     * Recebe e trata a url recebida
     *
     * @return string
     */
    public static function getUri()
    {
        if (!isset($_SERVER['QUERY_STRING'])) {
            return 'index';
        }
        
        $uri = substr($_SERVER['QUERY_STRING'], 1);

        if (substr($uri, strlen($uri) - 1) == '/') {
            $uri = substr($uri, 0, strlen($uri) - 1);
        }

        return $uri;
    }

    /**
     * Processa a url recebida e faz a chamada dos controllers correspondentes.
     *
     * @return void
     */
    public static function run()
    {
        $route = self::getUri();

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
                $args = array_values($uri);
                break;
            default:
                exit('Unknown route');
                break;
        }

        require(CONTROLLER_PATH.DIRECTORY_SEPARATOR.$class.'.php');
        $class = "\App\\".ucfirst($class);

        $Controller = new $class();
        if ($args) {
            switch (count($args)) {
                case 1:
                    $Controller->$method($args[0]);
                    break;
                case 2:
                    $Controller->$method($args[0], $args[1]);
                    break;
                case 3:
                    $Controller->$method($args[0], $args[1], $args[2]);
                    break;
            }
        } else {
            $Controller->$method();
        }
    }
}
