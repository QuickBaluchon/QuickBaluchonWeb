<?php

require_once('Api.php');

class ApiDeliveryMan extends Api {

  private $_method;
  private $_data = [];


  public function __construct($url, $method) {

    $this->_method = $method;


    if (count($url) == 0)
      $this->_data = $this->getListDelivery();     // list of packages - /api/deliveryman

    elseif ( ($id = intval($url[0])) !== 0 ){    // details one packages - /api/deliveryman/{id}
    switch ($method) {
        case 'GET':$this->_data = $this->getDelivery($id);break;
        case 'POST': $this->signupDeliveryman();break;
    }


  }

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

  public function signupDeliveryman() {
      echo "ok";
    $data = $this->getPostArray();
    $cols = ['firstname', 'lastname', 'phone', 'email', 'volumeCar', 'radius', 'IBAN','wharehouse', 'licenseImg', "registrationIMG"];
    for( $i = 0; $i < count($cols); $i++ ){
        if( !isset( $data[$cols[$i]] )){
            exit();
            http_response_code(400);
        }
        self::$_params[] = $data[$cols[$i]];
    }

      // check if a user already has this name
        self::$_columns = ['firstname', 'lastname', 'phone', 'email', 'volumeCar', 'radius', 'IBAN','wharehouse', 'licenseImg', "registrationIMG"];
        $this->add('DELIVERYMAN');
        //$this->login();



  }

}
