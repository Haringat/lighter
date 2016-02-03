<?php

namespace App\Router\Middleware;

/**
 * Class HTTPPathParser
 * @package App\Router\Middleware
 */
class HTTPPathParser implements IMiddleware
{

    /**
     * HTTPPathParser constructor.
     */
    public function __construct()
    {
    }

    /**
     * @inheritdoc
     */
    public function resolve(...$args)
    {
        $basepathparts = null;
        if (isset($args[0]) && gettype($args[0]) == "object" && get_class($args[0]) == \App\Router\Injector\Server)
            $basepathparts = explode("?", $_SERVER["REQUEST_URI"]);
        $_SERVER["REQUEST_URI"] = $basepathparts[0];
        if (isset($basepathparts[1])) {
            $getparams = explode("&", $basepathparts[1]);
            foreach ($getparams as $param) {
                $paramparts = explode("=", $param);
                $rawValue = urldecode($paramparts[1]);
                $htmlEscapedValue = htmlspecialchars($rawValue);
                $_GET[$paramparts[0]] = $htmlEscapedValue;
            }
        }
        foreach ($_POST as $name => $value) {
            $rawValue = urldecode($value);
            $htmlEscapedValue = htmlspecialchars($rawValue);
            $_POST[$name] = $htmlEscapedValue;
        }
    }

}
