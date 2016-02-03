<?php

namespace App\Router;

use App\Router\Injector\Injector;
use App\Router\Middleware\IMiddleware;

class Router
{

    /**
     * @var Engine $engine
     */
    private static $engine;

    /**
     * @var array $middleware
     */
    private static $middleware;

    public function __construct()
    {
    }

    public static function setEngine(Engine $engine)
    {
        self::$engine = $engine;
    }

    /**
     * register a middleware for later execution
     * @param string $name
     * @param IMiddleware $middleware
     * @param array $dependencies
     */
    public static function registerMiddleware(string $name, IMiddleware $middleware, $dependencies = array())
    {
        if (!isset(self::$middleware)) {
            self::$middleware = array();
        }
        self::$middleware[$name] = array(
            "middleware" => $middleware,
            "dependencies" => $dependencies
        );
    }

    public static function applyMiddleware()
    {
        if (!isset(self::$middleware)) {
            return;
        }
        foreach (self::$middleware as $name => $middleware) {
            syslog(LOG_INFO, "applying middleware " . $name);
            $dependencies = array();
            foreach ($middleware["dependencies"] as $dependency) {
                $dependencies[] = Injector::resolve($dependency);
            }
            print_r($dependencies);
            $middleware["middleware"]->resolve($dependencies);

        }
    }

    public static function serve()
    {
        self::applyMiddleware();
        $server = Injector::resolve("Server");
        echo "<pre>";
        print_r($server);
        echo "</pre>";
        self::$engine->route("/" . implode("/", array_diff(explode("/", $_SERVER["REQUEST_URI"]), explode("/", $_SERVER["SCRIPT_NAME"]))));
    }

    public static function register($route)
    {
        self::$engine->registerRoute($route);
    }

    public static function get($path, $callback)
    {
        self::register(Route::get($path, $callback));
    }

    public static function post($path, $callback)
    {
        self::register(Route::post($path, $callback));
    }

    public static function put($path, $callback)
    {
        self::register(Route::put($path, $callback));
    }

    public static function patch($path, $callback)
    {
        self::register(Route::patch($path, $callback));
    }

    public static function delete($path, $callback)
    {
        self::register(Route::delete($path, $callback));
    }

    public static function custom($path, $callback, $methods)
    {
        self::register(Route::custom($path, $callback, $methods));
    }

    public static function all($path, $callback)
    {
        self::register(Route::custom($path, $callback, array("GET", "POST", "PUT", "PATCH", "DELETE", "HEAD", "OPTIONS")));
    }

    public static function static ($path, $resource, $mime, $forceDownload)
    {
        self::register(Route::static ($path, $resource, $mime, $forceDownload));
    }

}
