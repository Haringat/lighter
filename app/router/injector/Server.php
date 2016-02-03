<?php

namespace App\Router\Injector;

use \App\Router\Injector\IInjectable;

class Server implements IInjectable
{

    public function __construct()
    {

    }

    public function resolve(...$args)
    {
        return $_SERVER;
    }

}
