<?php

namespace App\Router\Injector;

class Injector
{

    /**
     * @var array $injectables
     */
    private static $injectables;

    /**
     * @var array $cache
     */
    private static $cache;

    /**
     * register an injectable
     * @param string $name
     * @param IInjectable $injectable
     * @param array $dependencies
     */
    public static function registerInjectable(string $name, IInjectable $injectable, $dependencies = array())
    {
        $inj = array(
            "injectable" => $injectable,
            "dependencies" => $dependencies
        );
        self::$injectables[$name] = $inj;
    }

    /**
     * resolve all dependencies of $name recursively (supports caching)
     * @param string $name the name of the injectable
     * @return mixed
     */
    public static function resolve(string $name)
    {
        if (isset(self::$cache[$name])) {
            return self::$cache[$name];
        }
        $injectable = self::$injectables[$name];
        $injections = array();
        foreach ($injectable["dependencies"] as $dependency) {
            $injections[] = self::resolve($dependency);
            self::$cache[$name] = call_user_func_array("$injectable::resolve", $injections);
            return self::$cache[$name];
        }
    }

    /**
     * magic method
     * @param $name
     * @param $arguments
     */
    public static function __callStatic($name, $arguments)
    {
        if (!isset(self::$injectables)) {
            self::$injectables = array();
        }
        if (!isset(self::$cache)) {
            self::$cache = array();
        }
    }

}
