<?php

function my_autoload($class_name) {
    $class_path = __DIR__ . '/class/' . $class_name . '.php';
    if (file_exists($class_path)) {
        require_once $class_path;
    }
    
    $class_path = __DIR__ . '/controller/' . $class_name . '.php';
    if (file_exists($class_path)) {
        require_once $class_path;
    }

}

spl_autoload_register('my_autoload');