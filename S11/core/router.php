<?php

class Router
{
    private $routes = [
        'GET' => [],
        'POST' => []
    ];

    public function get($uri, $action)
    {
        $this->routes['GET'][$uri] = $action;
    }

    public function post($uri, $action)
    {
        $this->routes['POST'][$uri] = $action;
    }

    public function dispatch($uri, $method)
    {
        if (isset($this->routes[$method][$uri])) {
            $action = $this->routes[$method][$uri];

            list($controllerName, $methodName) = explode('@', $action);

            require_once "../app/controllers/$controllerName.php";

            $controller = new $controllerName();
            $controller->$methodName();
        } else {
            echo "404 - Page not found";
        }
    }
}