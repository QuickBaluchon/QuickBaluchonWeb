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
    $packages = [];
    if($this->_method != 'GET') $this->catError(405);

    //$this->authentication(['admin']);

    self::$_columns = ['id', 'weight', 'volume', 'address',	'email', 'delay', 'dateDelivery', 'status', 'excelPath', 'dateDeposit'];
    self::$_offset = isset($_GET['offset']) ? intval($_GET['offset']) : 0;
    self::$_limit = isset($_GET['limit']) ? intval($_GET['limit']) : 20;

    $list = $this->get('PACKAGE');
    if( $list != null ){
      foreach ($list as $package) {
        $package['url'] = API_ROOT . 'package/' . $package['id'];
        $packages[] = $package;
      }
    }
    return $packages;
  }

  public function getPackage($id): array {

    if($this->_method != 'GET') $this->catError(405);
    //$this->authentication(['admin'], [$id]);
    self::$_columns = ['id', 'weight', 'volume', 'address',	'email', 'delay', 'dateDelivery', 'status', 'excelPath', 'dateDeposit'];
    self::$_where[] = 'id = ?';
    self::$_params[] = $id;
    $client = $this->get('PACKAGE');
    if( count($client) == 1 )
      return $client[0];
    else
      return [];
  }


}


