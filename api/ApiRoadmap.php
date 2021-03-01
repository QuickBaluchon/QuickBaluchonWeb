<?php


require_once('Api.php');

class ApiRoadmap extends Api
{

  private $_method;
  private $_data = [];

  public function __construct($url, $method)
  {

    $this->_method = $method;

    if (count($url) == 0)
      $this->_data = $this->getListRoadmaps();     // list of bills - /api/bill

    elseif (($id = intval($url[0])) !== 0)      // details one bills - /api/bill/{id}
    switch ($method) {
        case 'GET':$this->_data = $this->getRoadmap($id);break;
    }


    echo json_encode($this->_data, JSON_PRETTY_PRINT);

  }

  public function getListRoadmaps(): array {
    if($this->_method != 'GET') $this->catError(405);

    //$this->authentication(['admin']);
    $columns = ['ROADMAP.id', 'kmTotal', 'timeTotal', 'nbPackages', 'currentStop', 'dateRoute', 'deliveryman'];
    if(isset($_GET['inner'])) {
        $columns[] = 'ROADMAP.kmTotal';
        $inner = explode(',',$_GET['inner']);
        self::$_join[] = [
            'type' => 'inner',
            'table' => $inner[0],
            'onT1' => $inner[1],
            'onT2' => $inner[2]
        ] ;
    }

    if(isset($_GET['client'])) {
      self::$_where[] = 'deliveryman = ?';
      self::$_params[] = intval($_GET['client']);
    }

    if(isset($_GET['date'])) {
        $date = explode('-', $_GET['date']);
        self::$_where[] = 'MONTH(dateRoute) = ?';
        self::$_params[] = $date[1];
    }

    $list = $this->get('ROADMAP', $columns);
    $bills = [];
    if( $list != null ){
      foreach ($list as $bill) {
        $bills[] = $bill;
      }
    }
    return $bills;
  }

  public function getRoadmap($id): array
  {
    if ($this->_method != 'GET') $this->catError(405);
    //$this->authentication(['admin'], [$id]);
    self::$_where[] = 'id = ?';
    self::$_params[] = $id;
    $columns = ['id', 'kmTotal', 'timeTotal', 'nbPackages', 'currentStop', 'dateRoute', 'deliveryman'];
    $client = $this->get('ROADMAP', $columns);
    if (count($client) == 1)
      return $client[0];
    else
      return [];
  }
}
