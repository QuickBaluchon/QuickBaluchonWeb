<?php

require_once('views/View.php');

class ControllerPackage {

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

          if ($url[0] === 'recieve') {
              $this->_data = 'Ok' ;
              echo json_encode( $this->_data, JSON_PRETTY_PRINT );
          }

          elseif ($this->getPackage($url[0]) != 0) {
              switch ($this->_data['status']) {
                  case 0:
                    $this->recievePackage() ;
                    break ;
                  case 1:
                    $this->inWarehouse() ;
                    break ;
                  case 2:
                    $this->deliverPackage() ;
                    break ;
                  case 3:
                    $this->retrieved() ;
                    break ;
                  case 4:
                    $this->sentBack() ;
                    break ;
              }
          } else
            http_response_code(404) ;
      } else
        http_response_code(404) ;
    }
  }

  private function getPackage ($id) {
      $this->_packageManager = new PackageManager() ;
      $this->_id = intval($id) ;
      $this->_data = $this->_packageManager->getPackage($this->_id, ['id', 'status', 'dateDeposit']) ;

      return count($this->_data) ;
  }

  private function inWarehouse () {
      echo "En cours de traitement dans l'entrepôt" ;
  }

  private function deliverPackage () {
      $this->_view = new View('Delivering') ;
      $this->_view->generateView([]) ;
  }

  private function recievePackage () {
      $package = $this->_packageManager->getPackage($this->_id, ['id', 'weight', 'volume', 'address', 'email', 'delay']) ;
      $this->_view = new View('Reception') ;
      $this->_view->generateView($package);
  }

  private function sentBack () {
      echo "Retour à l'expéditeur" ;
  }

  private function retrieved () {
      echo "Livré" ;
  }
}
