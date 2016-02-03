<?php

namespace App\Router\Injector;

interface IInjectable
{

    public function resolve(...$args);

}
