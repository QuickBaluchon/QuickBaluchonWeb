<?php
require_once('views/View.php');

Class ControllerClient {

  public function __construct($url) {
    $this->signout();
    //echo "OK";
  }

  private function signout() {
    unset($_SESSION['id']);
    header('location:'.WEB_ROOT);
  }

}