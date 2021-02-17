<?php

require_once('views/View.php');

class ControllerPackage {

  private $_id;
  private $_view ;

  public function __construct($url) {

    if( !isset($url) )
      // API NOT FOUND : 404
      http_response_code(404) ;
    else{

        if (count($url) == 0)
            echo 'All in one' ;
        else{
              $url = array_slice($url,1); // remove /api/
              if( strlen($url[0]) == 0 ){

                // DUCUMENTATION PACKAGES
                echo 'DOCUMENTATION';
              }

              elseif (strtolower($url[0]) == 'sign') {
                  if (isset($url[1])) {
                      $this->_id = intval($url[1]) ;
                      $this->_view = new View('Sign') ;
                      $this->_view->generateView([]) ;
                  }
                  else
                    http_response_code(404) ;
              }

      }
    }
  }
}
