<?php

namespace App\Router\Middleware;


interface IMiddleware
{

    public function __construct();

    /**
     * resolve all dependencies of this middleware
     * @param array ...$args
     * @return mixed
     */
    public function resolve(...$args);

}