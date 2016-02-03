<?php

use App\Router\Middleware\HTTPPathParser;
use \App\Router\Router;
use \App\Router\Engine;
use \App\Router\Injector\Injector;
use \App\Router\Injector\Server;

Router::setEngine(new Engine());
Router::registerMiddleware("HTTPPathParser", new HTTPPathParser(), array("Server"));
Injector::registerInjectable("Server", new Server());
