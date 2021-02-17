<?php

require_once('views/View.php');

class ControllerDeliveryman
{

    private $_PayslipManager;
    private $_DeliverymanManager;
    private $_id;

    public function __construct($url)
    {
    $_SESSION["id"] = 4;
    $this->_id = $_SESSION["id"];
    if (!isset($url[1])) {
      header('location:' . WEB_ROOT . 'deliveryman/payslip');
      exit();


    }

    $actions = ['payslip', 'profile', "signup"];
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

    private function profile($url)
    {
        $this->_view = new View('Back');

        $this->_DeliverymanManager = new DeliveryManager();
        $delivery = $this->_DeliverymanManager->getDelivery($this->_id, ["firstname", "lastname", "phone", "email", "licenseImg", "registrationIMG", "volumeCar", "radius"]);
        $profile = $this->_view->generateTemplate('deliveryman_profile', $delivery);
        $this->_view->generateView(['content' => $profile, 'name' => $delivery['lastname']  ]);
    }
    private function signup($url)
    {
        $this->_view = new View('Back');

        $this->_DeliverymanManager = new DeliveryManager();
        $delivery = $this->_DeliverymanManager->getDelivery($this->_id, ["firstname", "lastname", "phone", "email", "licenseImg", "registrationIMG", "volumeCar", "radius"]);
        $profile = $this->_view->generateTemplate('signup_deliveryman', $delivery);
        $this->_view->generateView(['content' => $profile, 'name' => $delivery['lastname']  ]);
    }

}
