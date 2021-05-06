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
            if (isset($_SESSION['role']) && $_SESSION['role'] == 'deliveryman') {
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
        $payslips = $this->_PayslipManager->getPayslip(["id", "grossAmount", "bonus", "netAmount", "datePay", "paid", "pdfPath"], $this->_id);

        $buttonsValues = [
            'package' => 'visualiser',

        ];

        if($payslips != null){
            foreach ($payslips as $payslip) {
                $payslip['paid'] = $payslip['paid'] == 1 ? '<a href="'. WEB_ROOT . "paidPayslip/" . $payslip['id'] .'.pdf"><button type="button" class="btn btn-primary btn-sm">Paiement</button></a>' : "&#x10102" ;
                foreach($buttonsValues as $link => $inner){
                    if($payslip["pdfPath"] != NULL){
                        $buttons[] = '<a href="'. WEB_ROOT . "payslip/" . $payslip['id'] .'.pdf"><button type="button" class="btn btn-primary btn-sm">' . $inner . '</button></a>';
                        unset($payslip["pdfPath"]);
                    }else
                        $buttons[] = '<span> mois non terminé</span';
              }
              $rows[] = array_merge($payslip, $buttons);
              $buttons = [];

            }
        }else {
            $rows = [];
        }

        $cols = ["id", "grossAmount", "bonus", "datePay", "paid", "visualiser"];
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
        $roadmap = $this->_roadmapManager->getRoadmap(null, ["id", "kmTotal", "timeTotal", "nbPackages", "datePay", "currentStop", 'delivery'], null, date("Y-m-d"));

        $this->_DeliverymanManager = new DeliveryManager();
        $delivery = $this->_DeliverymanManager->getDelivery($this->_id, ["firstname", "lastname", "phone", "email", "licenseImg", "registrationIMG", "volumeCar", "radius"]);
        $roadmap = $this->_view->generateTemplate('deliveryman_roadmap', ['roadmap' => $roadmap, 'deliveryman' => $delivery]);

        $this->_view->generateView(['content' => $roadmap, 'name' => $delivery['lastname']]);
    }

}
