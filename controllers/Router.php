<?php

require_once('views/View.php');

class Router {
      private $_ctrl;
      private $_view;

      public function routeReq() {
        try {

          spl_autoload_register(function ($class) {
              if (substr_compare($class, "SendGrid", 0, strlen("SendGrid")))
                require_once('models/' . $class . '.php' );
          });

            $languagesManager = new LanguagesManager() ;
            if (!isset($_SESION['langs']))
                $_SESSION['langs'] = $languagesManager->getLanguages() ;
            if (!isset($_SESSION['defaultLang']))
                $_SESSION['defaultLang'] = $_SESSION['langs']['FR'] ;


            $url = '';
            var_dump($_GET);
            die();
          if( isset($_GET['url']) && strlen($_GET['url']) > 0) {
            $url = explode( '/', filter_var($_GET['url'],
            FILTER_SANITIZE_URL) );

            $controller = ucfirst( strtolower( $url[0] ) );
            $controllerClass = 'Controller' . $controller;
            $controllerFile = 'controllers/' . $controllerClass . '.php';


            if( file_exists($controllerFile) ) {
              require_once($controllerFile);
              $this->_ctrl = new $controllerClass($url);

            }else
              throw new Exception('<p>Page no found<p> <img src="https://http.cat/404.jpg" alt="404">');

          }else {
            require_once('controllers/ControllerHome.php');
            $this->_ctrl = new ControllerHome($url);
          }
        }
        catch( Exception $e) {
          $msg = $e->getMessage();
          $this->_view = new View('Error');
          $this->_view->generateView(['msg' => $msg]);
        }
    }
}
