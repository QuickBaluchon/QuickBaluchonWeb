<?php
require_once('views/View.php');

Class ControllerClient {

  private $_clientManager;
  private $_view;
  private $_id;

  public function __construct($url) {
    $_SESSION['id'] = 1;
    $this->_id = $_SESSION['id'];
    if( !isset($_SESSION['id']) ){
      header('location:'.WEB_ROOT);
      exit();
    }
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

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, API_ROOT.'client/'. $this->_id);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    $client = json_decode(curl_exec($ch), true);
    curl_close($ch);

    //$client = ['name' => 'Theo', 'website' => 'theo.fr'];
    $profile = $this->_view->generateTemplate('client_profile', $client);

    $this->_view->generateView(['content' => $profile, 'name' => $client['website']  ]);
  }

}