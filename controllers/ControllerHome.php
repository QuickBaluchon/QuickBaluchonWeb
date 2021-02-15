<?php

require_once('views/View.php');

class ControllerHome {

  private $_clientManager;
  private $_view;

  public function __construct($url){
    if( isset($url) && ( !is_string($url) && count($url) > 1 )   ){
      http_response_code(404);
      throw new Exception("Page not found");
    }
    else{
      $this->_view = new View('Home');
      $this->_view->generateView([]);
    }
  }

}
/*
  private function clients(){
    $this->_clientManager = new ClientManager;
    $clients = $this->_clientManager->getClients();

    $this->_view = new View('Home');
    $this->_view->generate(['clients' => $clients]);
  }


*/