<?php

require_once "class/Router.php";
require_once "config.php";

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

set_time_limit(120);

session_start();

if(!isset($_SESSION['token'])){
    $_SESSION['token'] = bin2hex(random_bytes(32));
}

$router = new Router();

$router->run();