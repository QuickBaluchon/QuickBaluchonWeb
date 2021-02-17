<?php

require_once('Api.php');

class ApiPricelist extends Api {

  private $_method;
  private $_data = [];
  public function __construct($url, $method) {

    $this->_method = $method;

    if (count($url) == 0)
      $this->_data = $this->getListPrice();     // list of packages - /api/package

    elseif ( ($id = intval($url[0])) !== 0 )      // details one packages - /api/package/{id}
      $this->_data = $this->getPrice($id);

    echo json_encode( $this->_data, JSON_PRETTY_PRINT );

  }

  public function getListPrice (): array  {
    $packages = [];
    if($this->_method != 'GET') $this->catError(405);

    if(isset($_GET['pricelist'])) {
      self::$_where[] = 'pricelist = ?';
      self::$_params[] = intval($_GET['pricelist']);
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


}
