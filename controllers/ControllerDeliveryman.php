<?php

require_once('views/View.php');

class ControllerDeliveryman
{
    private $_PayslipManager;
    private $_DeliverymanManager;
    private $_StatisticsManager;
    private $_roadmapManager;
    private $_id;

    public function __construct($url) {
        if( isset( $_SESSION['id'] ) )
            $this->_id = $_SESSION["id"];
        if (!isset($url[1])) {
            header('location:' . WEB_ROOT . 'deliveryman/profile');
            exit();
        }

        $actions = ['payslip', 'profile', 'statistics', "signup"];
        if (method_exists($this, $url[1])) {
            $method = $url[1];
            $this->$method(array_slice($url, 2));
        } else {
            http_response_code(404);
        }
    }

    private function payslip($url) {
        $this->_view = new View('Back');
        $this->_PayslipManager = new PayslipManager;
        $payslips = $this->_PayslipManager->getPayslip(["id", "grossAmount", "bonus", "netAmount", "datePay", "paid"], $this->_id);

        $buttonsValues = [
            'visualiser' => 'visualiser',

        ];

        if($payslips != null){
            foreach ($payslips as $payslip) {
                foreach($buttonsValues as $link => $inner){
                $buttons[] = '<a href="'. WEB_ROOT . "deliveryman/$link/" . $payslip['id'] .'"><button type="button" class="btn btn-primary btn-sm">' . $inner . '</button></a>';
              }
              $rows[] = array_merge($payslip, $buttons);
              $buttons = [];
            }
        }else {
            $rows = [];
        }

        $cols = ["id", "grossAmount", "bonus", "netAmount", "datePay", "paid", "visualiser"];
        $paySlip = $this->_view->generateTemplate('table', ['cols' => $cols, 'rows' => $rows]);
        $this->_view->generateView(['content' => $paySlip, 'name' => 'QuickBaluchon']);
    }

    private function profile($url) {
        $this->_view = new View('Back');

        $this->_DeliverymanManager = new DeliveryManager();
        $delivery = $this->_DeliverymanManager->getDelivery($this->_id, ["firstname", "lastname", "phone", "email", "licenseImg", "registrationIMG", "volumeCar", "radius"]);
        $profile = $this->_view->generateTemplate('deliveryman_profile', $delivery);
        $this->_view->generateView(['content' => $profile, 'name' => $delivery['lastname']]);
    }

    private function statistics($url) {
        $this->_view = new View('Back');

        $this->_DeliverymanManager = new DeliveryManager();
        $delivery = $this->_DeliverymanManager->getDelivery($this->_id, ["firstname", "lastname", "phone", "email", "licenseImg", "registrationIMG", "volumeCar", "radius"]);
        $profile = $this->_view->generateTemplate('deliveryman_stats', $delivery);
        $this->_view->generateView(['content' => $profile, 'name' => $delivery['lastname']]);
    }

    private function signup($url) {
        $this->_view = new View('SignupDeliveryman');

        $this->_DeliverymanManager = new DeliveryManager();
        $delivery = $this->_DeliverymanManager->getDelivery($this->_id, ["firstname", "lastname", "phone", "email", "licenseImg", "registrationIMG", "volumeCar", "radius"]);
        $this->_view->generateView();
    }

    public function visualiser($id){
        $this->_roadmapManager = new RoadmapManager;
        $this->_PayslipManager = new PayslipManager;
        $this->_DeliverymanManager = new DeliveryManager;

        $payslips = $this->_PayslipManager->getPayslip(["datePay"], NULL, $id[0]);
        $km = $this->_roadmapManager->getRoadmaps(["kmTotal"], NULL, $payslips[0]["datePay"], $this->_id);
        $priceKm = $this->calculKm($km);

        $date = explode("-", $payslips[0]["datePay"]);
        $nbColisDelivered = $this->_DeliverymanManager->getNumberPackageDelivered($this->_id, $date[1], $date[0]);
        $primeDelivered = $this->calculPrimeDelivered($nbColisDelivered);

        $heavyPackages = $this->_DeliverymanManager->getHeavyPackage($this->_id, $date[1], $date[0]);
        $primeHeavy = $this->calculPrimeHeavy($heavyPackages);


    }

    public function calculKm($kmTotal){
        $total = 0;
        foreach ($kmTotal as $km) {
            $total += $km['kmTotal'];
        }
        return $total * 0.36;
    }

    public function calculPrimeDelivered($nbColisDelivered){
        $total = 0;

        foreach ($nbColisDelivered as $key => $value) {
            $total += $value*1.90;
        }
        return $total;

    }

    public function calculPrimeHeavy($heavyPackages){
        $total = 0;
        foreach ($heavyPackages as $heavyPackage) {
            $total += (intval(($heavyPackage['weight']-30)/22)+1)*3 ;
        }
        return $total;
    }

}
