<?php

class Client {
  private $_id;
  private $_name;
  private $_paymentMethod;

  public function __construct( array $data ) {
    $this->hydrate($data);
  }

  // HYDRATE
  public function hydrate(array $data) {
    foreach ($data as $k => $v) {
      $method = 'set' . ucfirst($k);
      if( method_exists($this, $method) )
        $this->$method($v);
    }
  }


  // SETTERS
  public function setId($id) {
    $id = (int) $id;
    if($id > 0)
      $this->_id = $id;
  }

  public function setName($name) {
    if( is_string($name) )
      $this->_name = $name;

  }

  public function setPaymentMethod($paymentMethod) {
    if( is_string($paymentMethod) )
      $this->_paymentMethod = $paymentMethod;
  }


  // GETTERS
  public function id() {
    return $this->_id;
  }

  public function name() {
    return $this->_name;
  }

  public function paymentMethod() {
    return $this->_paymentMethod;
  }

}
