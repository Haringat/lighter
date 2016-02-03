<?php

namespace App\Router;

use App\Router\Router;

class Route
{

    private $path;
    private $callback;
    private $methods;
    private $resource;
    private $mime;
    private $forceDownload;
    private $isStatic;
    private $subRoutes;
    private $regex;

    private function __construct($path, $callback, $methods)
    {
        $this->subRoutes = array();
        $this->path = $path;
        $this->callback = $callback;
        $this->methods = $methods;
        $this->regex = self::createRegex($path);
    }

    private function setStatic($resource, $mime, $forceDownload)
    {
        $this->resource = $resource;
        $this->mime = $mime;
        $this->forceDownload = $forceDownload;
        $this->isStatic = true;
    }

    public static function get($path, $callback)
    {
        return new Route($path, $callback, array("GET"));
    }

    public static function post($path, $callback)
    {
        return new Route($path, $callback, array("POST"));
    }

    public static function put($path, $callback)
    {
        return new Route($path, $callback, array("PUT"));
    }

    public static function patch($path, $callback)
    {
        return new Route($path, $callback, array("PATCH"));
    }

    public static function delete($path, $callback)
    {
        return new Route($path, $callback, array("DELETE"));
    }

    public static function custom($path, $callback, $methods)
    {
        if (is_array($methods))
            return new Route($path, $callback, $methods);
        else if (is_string($methods))
            return new Route($path, $callback, array($methods));
    }

    public static function static ($path, $resource, $mime, $forceDownload)
    {
        $route = new Route($path, null, array("GET"));
        $route->setStatic($resource, $mime, $forceDownload);
        return $route;
    }

    public function getPath()
    {
        return $this->path;
    }

    public function getMethods()
    {
        return $this->methods;
    }

    public function getRegex()
    {
        return $this->regex;
    }

    public function serve($params)
    {
        if ($this->isStatic === true) {
            $this->serveStatic();
        } else {
            call_user_func_array($this->callback, $params);
        }
    }

    public function addSubRoute($route)
    {
        $this->subRoutes[] = $route;
    }

    public function getSubRoutes()
    {
        return $this->subRoutes;
    }

    private static function createRegex($path)
    {
        $routeparts = explode("/", $path);
        $ret = "/^";
        for ($i = 0; $i < count($routeparts); $i++) {
            if (trim($routeparts[$i]) == "") {
                continue;
            }
            if ($routeparts[$i][0] == ':' || $routeparts[$i][0] == '{') {
                $ret .= "\/.*?";
            } else {
                $ret .= "\/" . $routeparts[$i];
            }
        }
        return $ret . "\/?$/";
    }

    private function serveStatic()
    {
        $res = fopen($this->resource, "r");
        if (is_bool($res) && $res === false) {
            header("HTTP/1.1 404 NOT FOUND");
            return;
        }
        $content = fread($res, fstat($res)["size"]);
        fclose($res);
        if (isset($this->forceDownload) && $this->forceDownload === true) {
            $filename = "";
            $pathparts = explode("/", $this->resource);
            $filename = $pathparts[count($pathparts) - 1];
            header("Content-Disposition: attachment; filename=\"" . $filename . "\"");
        }
        header("Content-Type: " . $this->mime);
        echo $content;
    }

}
