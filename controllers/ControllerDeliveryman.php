<?php

require_once('views/View.php');

class ControllerDeliveryman
{

    private $_PayslipManager;
    private $_id;

    public function __construct($url)
    {
    $_SESSION["id"] = 4;
    $this->_id = $_SESSION["id"];
    if (!isset($url[1])) {
      header('location:' . WEB_ROOT . 'deliveryman/payslip');
      exit();


    }

    $actions = ['payslip'];
    if (in_array($url[1], $actions)) {
      $method = $url[1];
      $this->$method(array_slice($url, 2));

    } else {
      http_response_code(404);
    }
    }

    private function payslip($url)
    {
      $this->_view = new View('Back');
      $this->_PayslipManager = new PayslipManager;
      $list = $this->_PayslipManager->getPayslip($this->_id, []);


      $cols = ["id", "grossAmount", "bonus", "netAmount", "datePay", "pdfPath",	"paid"];
      $paySlip = $this->_view->generateTemplate('table', ['cols' => $cols, 'rows' => $list]);
      $this->_view->generateView(['content' => $paySlip, 'name' => 'QuickBaluchon']);
    }

}
