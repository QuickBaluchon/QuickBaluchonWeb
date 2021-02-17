<?php

require_once('Api.php');

class ApiPayslip extends Api {

  private $_method;
  private $_data = [];
  private $id = 5;
  public function __construct($url, $method) {

    $this->_method = $method;

    if (count($url) == 0)
      $this->_data = $this->getListPayslip();     // list of packages - /api/package

    elseif ( ($id = intval($url[0])) !== 0 ){     // details one packages - /api/package/{id}
      $this->_data = $this->getPayslip($id);
}
    echo json_encode( $this->_data, JSON_PRETTY_PRINT );

  }

  public function getListPayslip (): array  {
    $packages = [];
    if($this->_method != 'GET') $this->catError(405);

    if(isset($_GET['deliveryman'])) {
      self::$_where[] = 'deliveryman = ?';
      self::$_params[] = intval($_GET['deliveryman']);
    }
    //$this->authentication(['admin']);


    self::$_columns = ["id", "grossAmount", "bonus", "netAmount", "datePay", "pdfPath",	"paid"];
    self::$_offset = isset($_GET['offset']) ? intval($_GET['offset']) : 0;
    self::$_limit = isset($_GET['limit']) ? intval($_GET['limit']) : 20;

    $Payslip = $this->get('PAYSLIP', self::$_columns);

    return $Payslip;
  }

  public function getPayslip($id): array {

    if($this->_method != 'GET') $this->catError(405);
    //$this->authentication(['admin'], [$id]);
    $columns = ["id", "grossAmount", "bonus", "netAmount", "datePay", "pdfPath", "paid" ];
    self::$_where[] = 'id = ?';
    self::$_params[] = self::$id;
    $Payslip = $this->get('PAYSLIP', $columns);
    if( count($Payslip) == 1 )
      return $Payslip[0];
    else
      return [];
  }


}
