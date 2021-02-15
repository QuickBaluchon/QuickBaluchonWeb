<?php
require_once('views/View.php');

class ControllerLogin {

  public function __construct($url) {
    if( isset($url) && ( !is_string($url) && count($url) > 1 )   ){
      http_response_code(404);
      throw new Exception("Page not found");
    }
    else{
      $this->_view = new View('Login');
      $this->_view->generate([]);
    }
  }

}