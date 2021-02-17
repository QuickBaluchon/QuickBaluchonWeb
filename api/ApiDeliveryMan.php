<?php

require_once('Api.php');

class ApiDeliveryMan extends Api {

  private $_method;
  private $_data = [];


  public function __construct($url, $method) {

    $this->_method = $method;


    if (count($url) == 0)
      $this->_data = $this->getListDelivery();     // list of packages - /api/deliveryman

    elseif ( ($id = intval($url[0])) !== 0 )      // details one packages - /api/deliveryman/{id}
      $this->_data = $this->getDelivery($id);

    echo json_encode( $this->_data, JSON_PRETTY_PRINT );

  }

  public function getListDelivery (): array  {
    $packages = [];
    if($this->_method != 'GET') $this->catError(405);

    if(isset($_GET['deliveryman'])) {
      self::$_where[] = 'deliveryman = ?';
      self::$_params[] = intval($_GET['deliveryman']);
    }


    $columns = ['id', 'firstname', 'lastname', 'phone', 'email', 'volumeCar', 'radius', 'IBAN','employed', 'wharehouse', 'licenseImg', "registrationIMG"];
    self::$_offset = isset($_GET['offset']) ? intval($_GET['offset']) : 0;
    self::$_limit = isset($_GET['limit']) ? intval($_GET['limit']) : 20;

    $list = $this->get('DELIVERYMAN', $columns);

    return $list;
  }

  public function getDelivery($id): array {

    if($this->_method != 'GET') $this->catError(405);
    $columns = ['id', 'firstname', 'lastname', 'phone', 'email', 'volumeCar', 'radius', 'IBAN','employed', 'wharehouse', 'licenseImg', "registrationIMG"];
    self::$_where[] = 'id = ?';
    self::$_params[] = $id;
    $delivery = $this->get('DELIVERYMAN', $columns);
    if( count($delivery) == 1 )
      return $delivery[0];
    else
      return [];
  }


}
