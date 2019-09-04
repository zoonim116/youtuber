<?php

/**
 * Dynamically loads the class attempting to be instantiated elsewhere in the
 * plugin.
 *
 * @package Tutsplus_Namespace_Demo\Inc
 */
 

 
/**
 * Dynamically loads the class attempting to be instantiated elsewhere in the
 * plugin by looking at the $class_name parameter being passed as an argument.
 *
 * The argument should be in the form: TutsPlus_Namespace_Demo\Namespace. The
 * function will then break the fully-qualified class name into its pieces and
 * will then build a file to the path based on the namespace.
 *
 * The namespaces in this plugin map to the paths in the directory structure.
 *
 * @param string $class_name The fully-qualified name of the class to load.
 */
function youtuber_autoload ($pClassName) {
    $pClassName = str_replace("\\","/",$pClassName);
    $route = __DIR__ . str_replace("YoutubeR","",$pClassName) . ".php";
    if (file_exists($route)) include $route;
}

spl_autoload_register( 'youtuber_autoload' );
