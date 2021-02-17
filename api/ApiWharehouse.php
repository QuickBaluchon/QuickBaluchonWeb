<?php

require_once('Api.php');

class ApiWharehouse extends Api {

  private $_method;
  private $_data = [];

  public function __construct($url, $method) {

    $this->_method = $method;

    if (count($url) == 0)
      $this->_data = $this->getListWharehouse();     // list of packages - /api/wharehouse

    elseif ( ($id = intval($url[0])) !== 0 )      // details one packages - /api/wharehouse/{id}
      $this->_data = $this->getWharehouse($id);

    echo json_encode( $this->_data, JSON_PRETTY_PRINT );

  }

  public function getListWharehouse (): array  {
    $packages = [];
    if($this->_method != 'GET') $this->catError(405);

    //$this->authentication(['admin']);

    self::$_columns = ['id', 'address', 'volume', 'AvailableVolume','active'];
    self::$_offset = isset($_GET['offset']) ? intval($_GET['offset']) : 0;
    self::$_limit = isset($_GET['limit']) ? intval($_GET['limit']) : 20;

    $list = $this->get('WHAREHOUSE');

    return $list;
  }

  public function getWharehouse($id): array {

    if($this->_method != 'GET') $this->catError(405);
    //$this->authentication(['admin'], [$id]);
    self::$_columns = ['id', 'address', 'volume', 'AvailableVolume','active'];
    self::$_where[] = 'id = ?';
    self::$_params[] = $id;
    $wharehouse = $this->get('WHAREHOUSE');
    if( count($wharehouse) == 1 )
      return $wharehouse[0];
    else
      return [];
  }


}
