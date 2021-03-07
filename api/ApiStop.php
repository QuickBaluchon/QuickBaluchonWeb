<?php

require_once('Api.php');

class ApiStop extends Api {

  private $_method;
  private $_data = [];

  public function __construct($url, $method) {

    $this->_method = $method;

    // if (count($url) == 0)
    //   $this->_data = $this->getListPackages();     // list of packages - /api/package

    if ( ($pkg = intval($url[0])) !== 0 ) {     // details one packages - /api/package/{id}
        switch ($method) {
            case 'GET': $this->_data = $this->getStop($pkg);break;
            case 'PATCH': $this->_data = $this->updateStop($pkg);break;
            case 'DELETE': $this->_data = $this->stopRound($pkg); break ;
        }

        echo json_encode( $this->_data, JSON_PRETTY_PRINT );
    }
  }

  public function getStop ($pkg):array {
      if($this->_method != 'GET') $this->catError(405);

      $columns = ['roadmap', 'package', 'delivery'];
      self::$_where[] = 'package = ?';
      self::$_params[] = $pkg;
      self::$_where[] = 'delivery = ?';
      self::$_params[] = 'NULL';
      self::$_order = 'roadmap DESC' ;
      self::$_limit = 1 ;
      $package = $this->get('STOP', $columns);
      if( count($package) == 1 )
        return $package[0];
      else
        return [];
  }

  public function updateStop ($pkg) {
      $sql = "UPDATE STOP SET delivery = now() WHERE package = $pkg AND delivery IS NULL" ;
      $stmt = $this->getDb()->prepare($sql);
      if ($stmt) {
        $success = $stmt->execute(self::$_params);
        if ($success) {
          // OK
          $this->resetParams();
          http_response_code(200);
        } else {
          http_response_code(500);
        }
      } else {
        http_response_code(500);
      }
  }

  public function stopRound ($pkg) {
    $columns = []
  }
}
