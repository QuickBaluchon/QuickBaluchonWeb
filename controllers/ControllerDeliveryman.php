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
            if (isset($_SESSION['role']) && ($_SESSION['role'] == 'deliveryman' || $_SESSION['role'] == 'admin')) {
                $this->_id = $_SESSION['id'];
                $method = $url[1];
                $this->$method(array_slice($url, 2));
            } else
                $this->signup($url);
        } else {
            http_response_code(404);
        }
    }

    private function payslip($url) {
        $this->_view = new View('Back');
        $this->_PayslipManager = new PayslipManager;
        $payslips = $this->_PayslipManager->getPayslip(["id", "grossAmount", "bonus", "netAmount", "datePay", "paid"], $this->_id);

        $buttonsValues = [
            'package' => 'visualiser',

        ];

        if($payslips != null){
            foreach ($payslips as $payslip) {
                if($payslip['paid'] == 1){
                    $buttons[] = '<a href="'. WEB_ROOT . "uploads/paidPayslip/" . $payslip['id'] .'.pdf"><button type="button" class="btn btn-primary btn-sm">Visualiser</button></a>';
                }else{
                    $buttons[] = "<span>Mois non terminé</span>";
                }
                unset($payslip['paid']);
                $rows[] = array_merge($payslip, $buttons);
                $buttons = [];
            }

        }else {
            $rows = [];
        }

        $cols = ["id", "grossAmount", "bonus", "datePay", "visualiser"];
        $paySlip = $this->_view->generateTemplate('table', ['cols' => $cols, 'rows' => $rows]);
        $this->_view->generateView(['content' => $paySlip, 'name' => 'QuickBaluchon']);
    }

    private function profile($url) {
        $this->_view = new View('Back');

        $this->_DeliverymanManager = new DeliveryManager();
        if(isset($this->_id) && $_SESSION["role"] != "admin"){
            $delivery = $this->_DeliverymanManager->getDelivery($this->_id, ["firstname", "lastname", "phone", "email", "licenseImg", "registrationIMG", "volumeCar", "radius"]);
            $delivery['id'] = $_SESSION['id'];
        }

        else {
            $delivery = $this->_DeliverymanManager->getDelivery($url[0], ["firstname", "lastname", "phone", "email", "licenseImg", "registrationIMG", "volumeCar", "radius"]);
            $delivery['id'] = $url[0];
        }
        if( count($delivery) > 1 ){
            $profile = $this->_view->generateTemplate('deliveryman_profile', $delivery);
        }
        else {
            http_response_code(404);
            $profile = $this->_view->generateTemplate('Error', ['code'=>404]);
            $delivery['lastname'] = '';
        }


        $this->_view->generateView(['content' => $profile, 'name' => $delivery['lastname']]);
    }


    private function statistics($url) {
        $this->_view = new View('Back');

        $this->_DeliverymanManager = new DeliveryManager();
        $delivery = $this->_DeliverymanManager->getDelivery($this->_id, ["firstname", "lastname", "phone", "email", "licenseImg", "registrationIMG", "volumeCar", "radius"]);
        $stats = $this->_view->generateTemplate('deliveryman_stats', $delivery);
        $this->_view->generateView(['content' => $stats, 'name' => $delivery['lastname']]);
    }

    private function signup($url) {
        $this->_view = new View('SignupDeliveryman');

        $this->_warehouseManager = new WarehouseManager;
        $queryWarehouses = $this->_warehouseManager->getWarehouses(["id", "address"]);
        $warehouses = $this->_view->generateTemplate('selectWarehouses', ["warehouses" => $queryWarehouses]);


        $this->_view->generateView(["warehouses" => $warehouses]);
    }

    private function roadmap ($url) {
        $this->_view = new View('Back');
        $this->_view->_js[] = 'deliveryman/deliveries';
        $this->_roadmapManager = new RoadmapManager();
        $roadmap = $this->_roadmapManager->getRoadmap(null, ["id", "kmTotal", "timeTotal", "nbPackages", "currentStop", 'delivery'], null, date("Y-m-d"));

        $this->_DeliverymanManager = new DeliveryManager();
        $delivery = $this->_DeliverymanManager->getDelivery($this->_id, ["firstname", "lastname"]);
        $roadmap = $this->_view->generateTemplate('deliveryman_roadmap', ['roadmap' => $roadmap, 'deliveryman' => $delivery]);

        $this->_view->generateView(['content' => $roadmap, 'name' => $delivery['lastname']]);
    }

}
