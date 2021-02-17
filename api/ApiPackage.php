<?php

require_once('Api.php');

class ApiPackage extends Api {

  private $_method;
  private $_data = [];

  public function __construct($url, $method) {

    $this->_method = $method;

    if (count($url) == 0)
      $this->_data = $this->getListPackages();     // list of packages - /api/package

    elseif ( ($id = intval($url[0])) !== 0 )      // details one packages - /api/package/{id}
      $this->_data = $this->getPackage($id);

    echo json_encode( $this->_data, JSON_PRETTY_PRINT );

  }

  public function getListPackages (): array  {
    if($this->_method != 'GET') $this->catError(405);

    //$this->authentication(['admin']);

    if(isset($_GET['client'])) {
      self::$_where[] = 'client = ?';
      self::$_params[] = intval($_GET['client']);
    }

    if(isset($_GET['ordernb'])) {
      self::$_where[] = 'ordernb = ?';
      self::$_params[] = intval($_GET['ordernb']);
    }

    $columns = ['id', 'client', 'ordernb', 'weight', 'volume', 'address',	'email', 'delay', 'dateDelivery', 'status', 'excelPath', 'dateDeposit'];
    $list = $this->get('PACKAGE', $columns);
    $packages = [];
    if( $list != null ){
      foreach ($list as $package) {
        //$package['url'] = API_ROOT . 'package/' . $package['id'];
        $packages[] = $package;
      }
    }
    return $packages;
  }

  public function getPackage($id): array {
    if($this->_method != 'GET') $this->catError(405);
    //$this->authentication(['admin'], [$id]);
    $columns = ['id', 'weight', 'volume', 'address', 'email', 'delay', 'dateDelivery', 'status', 'excelPath', 'dateDeposit'];
    self::$_where[] = 'id = ?';
    self::$_params[] = $id;
    $package = $this->get('PACKAGE', $columns);
    if( count($package) == 1 )
      return $package[0];
    else
      return [];
  }


}
