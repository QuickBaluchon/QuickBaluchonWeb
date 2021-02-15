<?php
require_once('views/View.php');

Class ControllerClient {

  private $_clientManager;
  private $_view;
  private $_id;

  public function __construct($url) {
    $_SESSION['id'] = 1;
    if( !isset($_SESSION['id']) ){
      header('location:'.WEB_ROOT);
      exit();
    }
    $this->_id = $_SESSION['id'];

    if( !isset($url[1]) )
      header('location:'.WEB_ROOT.'client/profile');

    switch ($url[1]) {
      case 'profile': $this->profile($this->_id);
        break;

      default :
        echo 'default';
    }
    //$this->signout();
  }

  private function signout() {
    unset($_SESSION['id']);
    header('location:'.WEB_ROOT);
  }

  private function profile($id) {
    $this->_view = new View('Back');

    //  APPELLER LE MODÈLE CLIENT QUI POSSÈDE LES DONNÉES




    $profile = $this->_view->generateTemplate('client_profile', $client);
    $this->_view->generateView(['content' => $profile, 'name' => $client['website']  ]);
  }

}