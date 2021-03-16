<?php

require_once('views/View.php');

class ControllerDeliveryman
{
    private $_PayslipManager;
    private $_warehouseManager;
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

        $this->_warehouseManager = new WarehouseManager;
        $warehouses = $this->_warehouseManager->getWarehouses(["id", "address"]);

        foreach($warehouses as $warehouse) {
            $options[] = "<option value=" . $warehouse["id"] . ">" . $warehouse["address"] . "</option>";
        }

        $this->_view->generateView(["options" => $options]);
    }

    public function visualiser($id){
        $this->_roadmapManager = new RoadmapManager;
        $this->_PayslipManager = new PayslipManager;
        $this->_DeliverymanManager = new DeliveryManager;

        $payslips = $this->_PayslipManager->getPayslip(["datePay"], NULL, $id[0]);

        $km = $this->_roadmapManager->getRoadmaps(["kmTotal"], NULL, $payslips[0]["datePay"], $this->_id);
        $priceKm = $this->calculKm($km);

        $date = explode("-", $payslips[0]["datePay"]);

        $nbTotalColisDelivered = $this->_DeliverymanManager->getNbTotalColisDelivered($this->_id, $date[1], $date[0]);
        $primeDelivered = $this->calculPrimeColisDelivered($nbTotalColisDelivered);

        $heavyPackages = $this->_DeliverymanManager->getHeavyPackage($this->_id, $date[1], $date[0]);
        $primeHeavy = $this->calculPrimeHeavy($heavyPackages);

        $nbTotalColis = $this->_DeliverymanManager->getNbTotalColis($this->_id, $date[1], $date[0]);
        $percent = $this->calculPrimePercentDelivered($nbTotalColisDelivered, $nbTotalColis);

        $salair = $this->calculTotal($priceKm,$primeDelivered,$primeHeavy,$percent);
        echo $salair;
    }

    public function calculKm($kmTotal){
        $total = 0;
        foreach ($kmTotal as $km) {
            $total += $km['kmTotal'];
        }
        return $total * 0.36;
    }

    public function calculPrimeColisDelivered($nbTotalColis){
        $total = 0;

        foreach ($nbTotalColis[0] as $date => $nb) {
                $total += $nb*1.90;
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

    public function calculPrimePercentDelivered($nbTotalColisDelivered, $nbTotalColis){
        $total = 0;

        foreach ($nbTotalColisDelivered[0] as $key => $nbTotal) {
                foreach ($nbTotalColis[0] as $key => $nb) {
                        $total = 100*$nbTotal/$nb;
                }
        }
        switch ($total) {
            case $total > 87:return 10;break;
            case $total >= 72 && $total <= 87:return 120;break;
            case $total >= 60 && $total < 72:return 50;break;
            case $total < 60:return 0;break;
            case $total < 10:return 15;break;
        }
    }

    public function calculTotal($priceKm,$primeDelivered,$primeHeavy,$percent){
        $salair = 1231 + $priceKm + $primeDelivered + $primeHeavy;
        switch ($percent) {
            case 10: $salair *= 1.10; break;
            case 120:$salair += 120; break;
            case 50:$salair += 50; break;
            case 0:$salair; break;
            case 15:$salair *= 0.85; break;
        }
        return $salair;

    }

}
