<?php

require_once('Api.php');

class ApiPayslip extends Api {

  private $_method;
  private $_data = [];
  private $id = 5;
  public function __construct($url, $method) {


    $this->_method = $method;

    if (count($url) == 0){
        switch ($method) {
            case 'GET': $this->_data = $this->getListPayslip();break;
            case 'POST': $this->addPayslip();break;
            default: $this->catError(405); break ;
        }
    }


    elseif ( ($id = intval($url[0])) !== 0 ){     // details one packages - /api/package/{id}
      $this->_data = $this->getPayslip($id);
}
    echo json_encode( $this->_data, JSON_PRETTY_PRINT );

  }

  public function getListPayslip (): array  {
    $packages = [];
    if($this->_method != 'GET') $this->catError(405);

    if(isset($_GET['deliveryman']) && $_GET['deliveryman'] != NULL) {
      self::$_where[] = 'deliveryman = ?';
      self::$_params[] = intval($_GET['deliveryman']);
    }
    if(isset($_GET['id']) && $_GET['id'] != NULL) {
      self::$_where[] = 'id = ?';
      self::$_params[] = intval($_GET['id']);
    }

    self::$_columns = ["id", "grossAmount", "bonus", "datePay", "pdfPath",	"paid"];
    self::$_offset = isset($_GET['offset']) ? intval($_GET['offset']) : 0;
    self::$_limit = isset($_GET['limit']) ? intval($_GET['limit']) : 20;

    $Payslip = $this->get('PAYSLIP', self::$_columns);

    return $Payslip;
  }

  public function getPayslip($id): array {

    if($this->_method != 'GET') $this->catError(405);
    //$this->authentication(['admin'], [$id]);
    $columns = ["id", "grossAmount", "bonus", "datePay", "pdfPath", "paid" ];
    self::$_where[] = 'id = ?';
    self::$_params[] = self::$id;
    $Payslip = $this->get('PAYSLIP', $columns);
    if( count($Payslip) == 1 )
      return $Payslip[0];
    else
      return [];
  }

  public function addPayslip(){
      $data = $this->getJsonArray();
      $allowed = ["grossAmount",'bonus', 'datePay', 'paid', 'deliveryman'];

      if( count(array_diff(array_keys($data), $allowed)) > 0 ) {
          http_response_code(400);
          exit(0);
      }

      self::$_columns = ["grossAmount",'bonus', 'datePay', 'paid', 'deliveryman'];
      self::$_params = [$data["grossAmount"], $data["bonus"], $data["datePay"], $data["paid"], $data["deliveryman"]];

      $this->add('PAYSLIP');
    }
  }
