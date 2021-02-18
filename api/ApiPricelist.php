<?php

require_once('Api.php');

class ApiPricelist extends Api {

  private $_method;
  private $_data = [];
  public function __construct($url, $method) {

    $this->_method = $method;

    if (count($url) == 0)
      $this->_data = $this->getListPrice();     // list of packages - /api/pricelist

      elseif ( ($id = intval($url[0])) !== 0 ) { // details one client - /api/pricelist/{id}
        switch ($method) {
          case 'GET' : $this->_data = $this->getPrice($id); break;
          case 'PATCH' : $this->updatePrice($id); break;
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
    //$this->authentication(['admin']);


    $_columns = ["id", "maxWeight", "ExpressPrice", "StandardPrice", "applicationDate"];
    self::$_offset = isset($_GET['offset']) ? intval($_GET['offset']) : 0;
    self::$_limit = isset($_GET['limit']) ? intval($_GET['limit']) : 20;

    $Payslip = $this->get('PRICELIST', $_columns);

    return $Payslip;
  }

  public function getPrice($id): array {

    if($this->_method != 'GET') $this->catError(405);
    //$this->authentication(['admin'], [$id]);
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
      $allowed = ['ExpressPrice', 'StandardPrice'];

      $columns = ['maxWeight'];
      self::$_where[] = 'id = ?';
      self::$_params[] = $id;
      $maxWeight = $this->get('PRICELIST', $columns);

      if( count(array_diff(array_keys($data), $allowed)) > 0 ) {
        http_response_code(400);
        exit(0);
      }

      self::$_columns = ['maxWeight', 'ExpressPrice', 'StandardPrice'];
      self::$_params = [$maxWeight[0]['maxWeight'],$data['ExpressPrice'], $data['StandardPrice']];

      $this->add('PRICELIST');
  }

}
