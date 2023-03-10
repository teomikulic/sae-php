<?php
$baseNamespaces = [
    "BubbleORM" => "Libs/BubbleORM",
    "Models" => "Models",
    "Enums" => "Enums",
    "Utils" => "Utils",
    "Managers" => "Managers",
];

spl_autoload_register('autoload');

/**
 * Inclue le fichier correspondant à notre classe
 * @param $class string Le nom de la classe à charger
 */
function autoload(string $className) : void{
    global $baseNamespaces;

    $result = $className;

    foreach($baseNamespaces as $nameSpace => $path){
        $classPath = preg_replace("/^$nameSpace/", $path, $className);
        if($classPath != $result){
            $result = $classPath;
            break;
        }
    }

    $result = preg_replace("/\\\\/", '/', $result);

    require_once "$result.php";
}