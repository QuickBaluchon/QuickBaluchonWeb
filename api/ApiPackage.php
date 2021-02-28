<?php

require_once('Api.php');

class ApiPackage extends Api {

  private $_method;
  private $_data = [];

  public function __construct($url, $method) {

    $this->_method = $method;

    if (count($url) == 0)
      $this->_data = $this->getListPackages();     // list of packages - /api/package

    elseif ( ($id = intval($url[0])) !== 0 ) {     // details one packages - /api/package/{id}
        switch ($method) {
            case 'GET': $this->_data = $this->getPackage($id);break;
            case 'PATCH': $this->_data = $this->updatePackage($id);break;
        }


    }

    echo json_encode( $this->_data, JSON_PRETTY_PRINT );

  }

  public function getListPackages (): array  {
    if($this->_method != 'GET') $this->catError(405);

    $columns = ['PACKAGE.id', 'client', 'ordernb', 'weight', 'volume', 'address', 'email', 'delay', 'dateDelivery', 'PACKAGE.status', 'excelPath', 'dateDeposit'];

    if(isset($_GET['inner'])) {
        $colums[] = ['PRICELIST.ExpressPrice', 'PRICELIST.ExpressPrice'];
        self::$_inner = explode(',',$_GET['inner']);
    }



    if(isset($_GET['client'])) {
      self::$_where[] = 'client = ?';
      self::$_params[] = intval($_GET['client']);
    }

    if(isset($_GET['ordernb'])) {
      self::$_where[] = 'ordernb = ?';
      self::$_params[] = intval($_GET['ordernb']);
    }
    if(isset($_GET['date'])) {
        $date = explode('-', $_GET['date']);
        self::$_where[] = 'DATE_FORMAT(dateDeposit, "%m") = ?';
        self::$_params[] = $date[1];
    }

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

    $columns = ['PACKAGE.id', 'client', 'ordernb', 'weight', 'volume', 'address', 'email', 'delay', 'dateDelivery', 'PACKAGE.status', 'excelPath', 'dateDeposit'];
    if(isset($_GET['inner'])) {
        $colums[] = ['PRICELIST.ExpressPrice', 'PRICELIST.ExpressPrice'];
        self::$_inner = explode(',',$_GET['inner']);
    }

    self::$_where[] = 'PACKAGE.id = ?';
    self::$_params[] = $id;
    $package = $this->get('PACKAGE', $columns);

    if( count($package) >= 1 )
      return $package[0];
    else
      return [];
  }

  public function updatePackage($id) {
      $data = $this->getJsonArray();
      $allowed = ['weight', 'volume', 'address', 'email', 'delay', 'status', 'dateDeposit', 'dateDelivery'];
      if( count(array_diff(array_keys($data), $allowed)) > 0 ) {
        http_response_code(400);
        exit(0);
      }

      foreach ($data as $key => $value) {
        self::$_set[] = "$key = ?";
        self::$_params[] = $value;
      }
      if ($data['status'] == 1) {
          self::$_set[] = "dateDeposit = now()" ;
          self::$_set[] = "dateDelivery = DATE_ADD(now(), INTERVAL ? DAY)" ;
          self::$_params[] = $data['delay'] ;
      }
      $this->patch('PACKAGE', $id);
  }


}
