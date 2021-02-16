<?php


require_once('Api.php');

class ApiBill extends Api
{

  private $_method;
  private $_data = [];

  public function __construct($url, $method)
  {

    $this->_method = $method;

    if (count($url) == 0)
      $this->_data = $this->getListBills();     // list of bills - /api/bill

    elseif (($id = intval($url[0])) !== 0)      // details one bills - /api/bill/{id}
      $this->_data = $this->getBill($id);

    echo json_encode($this->_data, JSON_PRETTY_PRINT);

  }

  public function getListBills(): array {
    if($this->_method != 'GET') $this->catError(405);

    //$this->authentication(['admin']);

    if(isset($_GET['client'])) {
      self::$_where[] = 'client = ?';
      self::$_params[] = intval($_GET['client']);
    }

    $columns = ['id', 'client', 'grossAmount', 'dateBill', 'pdfPath', 'paid'];
    $list = $this->get('MONTHLYBILL', $columns);
    $bills = [];
    if( $list != null ){
      foreach ($list as $bill) {
        $bills[] = $bill;
      }
    }
    return $bills;
  }

  public function getBill($id): array
  {
    if ($this->_method != 'GET') $this->catError(405);
    //$this->authentication(['admin'], [$id]);
    self::$_where[] = 'id = ?';
    self::$_params[] = $id;
    $columns = ['id', 'client', 'grossAmount', 'dateBill', 'pdfPath', 'paid'];
    $client = $this->get('MONTHLYBILL', $columns );
    if (count($client) == 1)
      return $client[0];
    else
      return [];
  }


}


