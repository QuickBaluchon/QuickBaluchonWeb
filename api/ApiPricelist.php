<?php

require_once('Api.php');

class ApiPricelist extends Api {

  private $_method;
  private $_data = [];
  public function __construct($url, $method) {

    $this->_method = $method;

    if (count($url) == 0){
        switch ($method) {                                              // list of packages - /api/pricelist
            case 'GET':$this->_data = $this->getListPrice();break;
            case 'POST': $this->addPrice();break;
        }
  }

      elseif ( ($id = intval($url[0])) !== 0 ) { // details one client - /api/pricelist/{id}
        switch ($method) {
          case 'GET' : $this->_data = $this->getPrice($id); break;
          case 'PATCH' : $this->updatePrice($id); break;
          case 'DELETE': $this->deletePricelist($id);
        }

      }

      elseif ( ($ExpressPrice = intval($url[1])) !== 0 )      // details one packages - /api/pricelist/ExpressPrice/{int}
        $this->_data = $this->getListPrice($ExpressPrice);

    echo json_encode( $this->_data, JSON_PRETTY_PRINT );

  }

  public function getListPrice ($ExpressPrice=NULL): array  {
    $packages = [];
    if($this->_method != 'GET') $this->catError(405);

    if(isset($_GET['pricelist'])) {
      self::$_where[] = 'pricelist = ?';
      self::$_params[] = intval($_GET['pricelist']);
    }

    if(isset($ExpressPrice)) {
      self::$_where[] = 'ExpressPrice = ?';
      self::$_params[] = intval($ExpressPrice);
    }

    self::$_where[] = 'status = 1';


    $_columns = ["id", "maxWeight", "ExpressPrice", "StandardPrice", "applicationDate"];
    self::$_offset = isset($_GET['offset']) ? intval($_GET['offset']) : 0;
    self::$_limit = isset($_GET['limit']) ? intval($_GET['limit']) : 20;

    $Payslip = $this->get('PRICELIST', $_columns);

    return $Payslip;
  }

  public function getPrice($id): array {

    if($this->_method != 'GET') $this->catError(405);

    $columns = ["id", "maxWeight", "ExpressPrice", "StandardPrice", "applicationDate"];
    self::$_where[] = 'id = ?';
    self::$_params[] = $id;
    $Payslip = $this->get('PRICELIST', $columns);
    if( count($Payslip) == 1 )
      return $Payslip[0];
    else
      return [];
  }

  public function updatePrice($id){

      $data = $this->getJsonArray();

      $allowed = ['ExpressPrice', 'StandardPrice', 'inputDate', 'status'];

      $columns = ['maxWeight'];
      self::$_where[] = 'id = ?';
      self::$_params[] = $id;
      $maxWeight = $this->get('PRICELIST', $columns);

      if( count(array_diff(array_keys($data), $allowed)) > 0 ) {
        http_response_code(400);
        exit(0);
      }
      $date = explode("/",$data['inputDate']);
      $date = array_reverse($date, true);
      $date = join("-", $date);

      self::$_columns = ['maxWeight', 'ExpressPrice', 'StandardPrice', 'applicationDate', 'status'];
      self::$_params = [$maxWeight[0]['maxWeight'],$data['ExpressPrice'], $data['StandardPrice'], $date, $data['status']];

      $this->add('PRICELIST');
  }

  public function deletePricelist($id) {
      $data = $this->getJsonArray();
      $allowed = ['status'];
      if( count(array_diff(array_keys($data), $allowed)) > 0 ) {
        http_response_code(400);
        exit(0);
      }

      foreach ($data as $key => $value) {
        self::$_set[] = "$key = ?";
        self::$_params[] = $value;
      }

      $this->patch('PRICELIST', $id);
  }

  public function addPrice(){

      $data = $this->getJsonArray();
      $allowed = ["maxWeight",'ExpressPrice', 'StandardPrice', 'applicationDate', 'status'];

     if( count(array_diff(array_keys($data), $allowed)) > 0 ) {
        http_response_code(400);
        exit(0);
      }

      $date = explode("/",$data['applicationDate']);
      $date = array_reverse($date, true);
      $date = join("-", $date);

      self::$_columns = ['maxWeight', 'ExpressPrice', 'StandardPrice', 'applicationDate', 'status'];
      self::$_params = [$data['maxWeight'],$data['ExpressPrice'], $data['StandardPrice'], $date, $data['status']];

      $this->add('PRICELIST');
  }

}
