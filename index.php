<?php

define('WEB_ROOT', str_replace('index.php', '', !empty($_SERVER['HTTPS']) ? "https": "http" . "://$_SERVER[HTTP_HOST]$_SERVER[PHP_SELF]"));
define('ROOT', str_replace('index.php', '', $_SERVER['SCRIPT_FILENAME']));
define('API_ROOT', WEB_ROOT . 'api/');

echo '*' . $_SERVER['HTTPS'];

// print errors
error_reporting(E_ALL);
ini_set("display_errors", 1);

require_once('controllers/Router.php');
session_start();

$router = new Router();
$router->routeReq();
