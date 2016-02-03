<?php

namespace App\Router;

class Engine
{

    private $routes;

    public function __construct()
    {
        $this->routes = array();
    }

    public function registerRoute($route)
    {
        $this->routes[] = $route;
    }

    private function getMethods($methods)
    {
        if (is_string($methods)) {
            return array(strtoupper($methods));
        }
        if (is_array($methods)) {
            for ($i = 0; $i < count($methods); $i++) {
                if (!is_string($methods[$i])) {
                    throw new Exception("Tried to register with a method which is not a string");
                }
                $methods[$i] = strtoupper($methods[$i]);
            }
            return $methods;
        }
        throw new Exception("Tried to register with a method which is not a string");
    }

    /**
     * route the user to the location to which he wants to go
     */
    public function route($basepath)
    {
        foreach ($this->routes as $route) {
            if (in_array($_SERVER["REQUEST_METHOD"], $route->getMethods())) {
                if (preg_match($route->getRegex(), $basepath) === 1) {
                    $pathparts = explode("/", $basepath);
                    $routeparts = explode("/", $route->getPath());
                    $params = array();
                    $paramcount = 0;
                    for ($i = 0; $i < count($pathparts); $i++) {
                        $pathpart = $pathparts[$i];
                        $routepart = $routeparts[$i];
                        // static url parts
                        if ($routepart != $pathpart) {
                            $params[] = $pathpart;
                            $paramcount++;
                        }
                    }
                    $route->serve($params);
                    return;
                }
            }
        }
    }
}
