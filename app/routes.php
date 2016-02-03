<?php

namespace App;

use App\Router\Engine as RouterEngine;
use App\Router\Router;
use App\Router\Route;

Router::setEngine(new RouterEngine());

Router::post("/hello/:name/attitude/:att", function ($name, $attitude) {
    echo "Hello " . $name . ". Your attitude is " . $attitude;
});

Router::static ("/pic", "/home/marcel/Bilder/Marcel_Mundl.JPG", "image/jpeg", false);

Router::all("/test", "App\Test::testPage");

Router::get("/hello/:greeter/aaa/:bbb", function ($a, $b) {
    echo "hello " . $a . " " . $b;
});

Router::all("/.*", function () {
    header("HTTP/1.1 403 FORBIDDEN");
});
