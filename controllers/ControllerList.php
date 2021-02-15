<?php

require_once('views/View.php');

class ControllerList {
  private $_view;

  public function __construct($url) {
    $this->_view = new View('List');
    $cols = ['Mois', 'Colis'];
    $rows =  [
      ['janv 2021', 28],
      ['dec 2020', 23]
    ] ;
    $this->_view->generate(["cols" => $cols, "rows" => $rows]);
  }

}