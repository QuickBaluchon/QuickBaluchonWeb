<?php

define('WEB_ROOT', str_replace('index.php', '', "https://$_SERVER[HTTP_HOST]$_SERVER[PHP_SELF]"));
define('ROOT', str_replace('index.php', '', $_SERVER['SCRIPT_FILENAME']));
define('API_ROOT', WEB_ROOT . 'api/');

echo WEB_ROOT;
echo ROOT;

// print errors
error_reporting(E_ALL);
ini_set("display_errors", 1);

require_once('controllers/Router.php');
session_start();

$router = new Router();
$router->routeReq();

// ----
/*

// get the method : GET / POST / PUT / DELETE / ...
$request_method = $_SERVER["REQUEST_METHOD"];

$url = '';
if( isset($_GET['url']) ){
  $url = explode('/', $_GET['url']);
}

if( $url == '' ){
  //accueil
  echo 'Accueil';
} else{
  switch ($url[0]) {
    case '':
      echo 'Accueil';
      break;

    case 'api':
      require('api/index.php' );
      break;

    default:
      // 404 not found
      echo '404';
      break;
  }
}


function select($pdo, $table, $cols, $where=null){
  $str = 'SELECT ';
  foreach ($cols as $col) $str .= $col . ',';
  $str = substr($str, 0, -1);
  $str .= ' FROM ' . $table;
  if( $where !== null ){
    $str .= ' WHERE ';
    foreach ($where as $key => $value) {
      $str .= $key . ' = ' . $value;
      $str .= ' AND ';
    }
    $str = substr($str, 0, -4);
  }

  $stmt = $pdo->query($str) ;
  if($stmt !== false){
    $response = array();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
      $response[] = $row;

    return $response;
  }else{
    echo "Error";
  }
}
*/
