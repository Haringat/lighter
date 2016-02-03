<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//require "../vendor/autoloader.php";

//use Slim\Slim;

//$app = new Slim();

//require "../app/routes.php";

//$app->run();

require_once "../app/jTraceEx.php";
require_once "../app/loader.php";
use \App\Router\Router;

try {
    require_once "../app/config.php";
    require_once "../app/routes.php";
    Router::serve();

} catch (\Exception $e) {
    echo "<pre>" . jTraceEx($e) . "</pre>";
}
