<?php
$protocol = strpos($_SERVER['HTTP_HOST'], 'ovh') === false ? 'http' : 'https';
define('WEB_ROOT', str_replace('index.php', '', $protocol . "://$_SERVER[HTTP_HOST]$_SERVER[PHP_SELF]"));
define('ROOT', str_replace('index.php', '', $_SERVER['SCRIPT_FILENAME']));
define('API_ROOT', WEB_ROOT . 'api/');

// print errors
error_reporting(E_ALL);
ini_set("display_errors", 1);

session_start();

//authorization
if(isset($_SESSION['jwt'])){
    header("Authorization:Bearer " . $_SESSION['jwt'] );
}

require_once('controllers/Router.php');

$router = new Router();
$router->routeReq();
