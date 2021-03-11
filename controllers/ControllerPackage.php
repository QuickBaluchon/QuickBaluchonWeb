<?php

require_once('views/View.php');

class ControllerPackage {
  private $_data;
  private $_id;
  private $_view ;
  private $_packageManager ;

  public function __construct($url) {
    if( !isset($url) )
      // API NOT FOUND : 404
      http_response_code(404) ;
    else {
      $url = array_slice($url,1); // remove /api/
      if( strlen($url[0]) == 0 ){

        // DUCUMENTATION PACKAGES
        echo 'DOCUMENTATION';
      }

      if (isset($url[0]) && !empty($url[0])) {
          if ($this->getPackage($url[0]) != 0) {
              if (isset($_SESSION['role']) && $_SESSION['role'] == 'deliveryman') {
                  if ($this->_data['status'] == 2) {
                      $v = $this->deliverPackage();
                  } else
                      $v = $this->viewExtern() ;
              } elseif (isset($_SESSION['role']) && ($_SESSION['role'] == 'staff' || $_SESSION['role'] == 'admin')) {
                  if ($this->_data['status'] == 0 || $this->_data['status'] == null) {
                      $v = $this->recievePackage();
                  } else
                      $v = $this->viewExtern() ;
              } else {
                  $v = $this->viewExtern() ;
              }
              $this->_view->generateView($v) ;
          } else
            http_response_code(404) ;
      } else
        http_response_code(404) ;
    }
  }

  private function getPackage ($id) {
      $this->_packageManager = new PackageManager() ;
      $this->_id = intval($id) ;
      $this->_data = $this->_packageManager->getPackage($this->_id, ['PACKAGE.id', 'PACKAGE.status', 'dateDeposit']) ;

      return count($this->_data) ;
  }

  private function deliverPackage () {
      $this->_view = new View('Delivering') ;
      return $this->_data ;
  }

  private function recievePackage () {
      $package = $this->_packageManager->getPackage($this->_id, ['PACKAGE.id', 'weight', 'volume', 'address', 'email', 'delay']) ;
      $this->_view = new View('Reception') ;
      return $package;
  }

  private function viewExtern () {
      $this->_view = new View('Package') ;
      return $this->_data ;
  }
}
