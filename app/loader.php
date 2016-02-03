<?php

/*function loadDir($path){
//	printf("loading directory %s\n", $path);
	$dir = opendir($path);
	while(($entry = readdir($dir)) !== false){
		if($entry == "." || $entry == ".."){
//			printf("skipping %s\n", $path."/".$entry);
			continue;
		}
		if(is_dir($path."/".$entry) === true){
//			printf("found directory %s\n", $path."/".$entry);
			loadDir($path."/".$entry);
		}
		if(is_file($path."/".$entry) === true && preg_match("/^.*?\.php$/",$path."/".$entry)){
//			printf("found php file %s. loading...\n", $path."/".$entry);
			require_once $path."/".$entry;
		}
	}
}

loadDir(__DIR__);
*/
session_start();
$contextparts = explode("/", $_SERVER["CONTEXT_DOCUMENT_ROOT"]);
unset($contextparts[count($contextparts) - 1]);
$rootPath = implode("/", $contextparts);
printf("%s", $rootPath);
/*require_once 'main.php';
require_once 'SQLConnect.php';
require_once 'Helper.php';
require_once $rootPath.DIRECTORY_SEPARATOR.'protected'.DIRECTORY_SEPARATOR.'controller'.DIRECTORY_SEPARATOR.'SessionController.php';*/
function __autoload($class_name)
{
    error_log("loading " . $class_name);
    $classparts = explode("\\", $class_name);
    $namespaceparts = array();
    for ($i = 0; $i < count($classparts) - 2; $i++) {
        $namespaceparts[$i] = strtolower($classparts[$i + 1]);
    }
    $classname = $classparts[count($classparts) - 1];
    $namespace = implode("/", $namespaceparts);
    require_once(($namespace != "" ? $namespace . "/" : "") . $classname . ".php");
    //$splitArr = preg_split('/(?=[A-Z])/',$class_name);
    /*if(!empty($splitArr)){
        end($splitArr);
        try {
            include_once ($rootPath.DIRECTORY_SEPARATOR.'$'.DIRECTORY_SEPARATOR.strtolower(current($splitArr)).DIRECTORY_SEPARATOR.$class_name.'.php');
        } catch (Exception $e) {
        }
    }*/
}
