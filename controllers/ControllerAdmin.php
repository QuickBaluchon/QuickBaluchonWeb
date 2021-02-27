<?php

require_once('views/View.php');

class ControllerAdmin {

    private $_adminManager;
    private $_clientManager;
    private $_WarehousesManager;
    private $_deliveryManager;
    private $_pricelistManager;
    private $_view;
    private $_notif;

    public function __construct($url) {
        if (!isset($url[1])) {
            if( isset($_SESSION['id'], $_SESSION['role'] ) && $_SESSION['role'] === 'admin' ) {
                header('location:' . WEB_ROOT . 'admin/pricelist');
                exit();
            }
            $this->login();
            exit();
        }
        if ($url[1] === 'login') {
            $this->trylogin();
            exit();
        }

        $_SESSION['role'] = 'admin';

        $actions = ['clients', 'pricelist', 'deliveryman', 'languages', 'warehouses', 'oneSignal', 'updatePricelist', 'employ', 'warehouseDetails'];
        if (method_exists($this, $url[1])) {
            $method = $url[1];
            $this->$method(array_slice($url, 2));

        } else {
            http_response_code(404);
        }
    }

    private function login() {
        $this->_view = new View('Admin');
        $this->_view->generateView();
    }

    private function trylogin() {
        $this->_adminManager = new AdminManager();
        if( $_SERVER['REQUEST_METHOD'] === 'POST' ) {
            $json = json_decode(file_get_contents('php://input'), true);
            if( isset( $json['username'], $json['password'] )) {

                 $admin = $this->_adminManager->login( $json['username'], $json['password']);
                 if( isset($admin['id'], $admin['role'], $admin['access_token']) ) {
                     $_SESSION['id'] = $admin['id'];
                     $_SESSION['role'] = $admin['role'];
                     echo json_encode($admin, JSON_PRETTY_PRINT);
                 } else
                     http_response_code(401);
            } else
                http_response_code(400);
        } else
            http_response_code(405);

    }

    private function clients($url) {
        $this->_view = new View('Back');

        $this->_clientManager = new ClientManager;
        $list = $this->_clientManager->getClients(['id', 'name']);

        $buttonsValues = [
            'profile' => 'données personnelles',
            'history' => 'Historique',
            'bills' => 'Factures'
        ];

        $rows = [];
        foreach ($list as $client) {
            foreach ($buttonsValues as $link => $inner) {
                $buttons[] = '<a href="' . WEB_ROOT . "client/$link/" . $client['id'] . '"><button type="button" class="btn btn-primary btn-sm">' . $inner . '</button></a>';
            }

            $rows[] = array_merge($client, $buttons);
            $buttons = [];
        }

        $cols = ['#', 'Nom', 'Données Personnelles', 'Historique', 'Facture'];
        $clients = $this->_view->generateTemplate('table', ['cols' => $cols, 'rows' => $rows]);
        $this->_view->generateView(['content' => $clients, 'name' => 'QuickBaluchon']);
    }

    private function oneSignal($url) {
        $this->_view = new View('OneSignal');
        $this->_view->generateView([]);
    }


    private function deliverymen($url) {
        $this->_view = new View('Back');

        $this->_DeliveryManager = new DeliveryManager;
        $list = $this->_DeliveryManager->getDeliverys([], NULL);


        $cols = ['#', 'firstname', 'lastname', 'phone', 'email', 'volumeCar', 'radius', 'IBAN', 'employed', 'warehouse'];
        $deliveryman = $this->_view->generateTemplate('table', ['cols' => $cols, 'rows' => $list]);
        $this->_view->generateView(['content' => $deliveryman, 'name' => 'QuickBaluchon']);
    }

    private function warehouses($url) {
        $this->_view = new View('Back');
        $this->_view->_js[] = 'warehouse/updateWarehouse';
        $this->_WarehousesManager = new WarehouseManager;
        $list = $this->_WarehousesManager->getWarehouses([]);
        if (!$list) {
            http_response_code(500);
            $list = [];
        }


        $buttonsValues = [
            'warehouseDetails' => 'Détails',
        ];

        foreach ($list as $warehouse) {
            foreach ($buttonsValues as $link => $inner) {
                $buttons[] = '<a href="' . WEB_ROOT . "admin/$link/" . $warehouse['id'] . '"><button type="button" class="btn btn-primary btn-sm">' . $inner . '</button></a>';
            }

            $rows[] = array_merge($warehouse, $buttons);
            $buttons = [];
        }

        $cols = ['#', 'address', 'volume', 'AvailableVolume', 'delete'];
        if (!isset($rows)) $rows = [];
        $warehouse = $this->_view->generateTemplate('table', ['cols' => $cols, 'rows' => $rows]);
        $this->_view->generateView(['content' => $warehouse, 'name' => 'QuickBaluchon']);

    }

    private function pricelist($url) {
        $this->_view = new View('Back');

        $this->_pricelistManager = new PricelistManager;
        $list = $this->_pricelistManager->getPricelists([]);
        if (!$list) $list = [];

        $buttonsValues = [
            'updatePricelist' => 'modifier',
        ];

        $rows = [];
        foreach ($list as $package) {
            foreach ($buttonsValues as $link => $inner) {
                $buttons[] = '<a href="' . WEB_ROOT . "admin/$link/" . $package['id'] . '"><button type="button" class="btn btn-primary btn-sm">' . $inner . '</button></a>';
            }

            $rows[] = array_merge($package, $buttons);
            $buttons = [];
        }

        $cols = ['#', 'Max wheight', 'Express price', 'Standard price', 'application date'];
        $pricelist = $this->_view->generateTemplate('table', ['cols' => $cols, 'rows' => $rows]);
        $this->_view->generateView(['content' => $pricelist, 'name' => 'QuickBaluchon']);
    }

    private function employ($url) {
        $this->_view = new View('Back');
        $this->_view->_js[] = 'deliveryman/employ';
        $this->_deliveryManager = new DeliveryManager;
        $list = $this->_deliveryManager->getDeliveryNotEmployed([]);

        $buttonsValues = [
            'employ' => 'employer',
        ];

     $buttonsValues = [
         'employ' => 'employer',
         'refuse' => 'refuser',
     ];

    foreach ($list as $delivery) {
        foreach($buttonsValues as $link => $inner){
        $buttons[] = '<button onclick="'. $link .'('.$delivery["id"].')" id="'.$delivery["id"].'" type="button" class="btn btn-primary btn-sm">' . $inner . '</button>';

      }

        $cols = ['id', 'firstname', 'lastname', 'phone', 'email', 'volumeCar', 'radius', 'IBAN', 'employed', 'warehouse', 'licenseImg', "registrationIMG", 'employer'];
        $delivery = $this->_view->generateTemplate('table', ['cols' => $cols, 'rows' => $rows]);
        $this->_view->generateView(['content' => $delivery, 'name' => 'QuickBaluchon']);
    }

    $cols = ['id', 'firstname', 'lastname', 'phone', 'email', 'volumeCar', 'radius', 'IBAN', 'employed', 'warehouse', 'licenseImg', "registrationIMG", 'employer', 'refuser'];
    $delivery = $this->_view->generateTemplate('table', ['cols' => $cols, 'rows' => $rows]);
    $this->_view->generateView(['content' => $delivery, 'name' => 'QuickBaluchon']);
  }

    private function updatePricelist($url) {
        $this->_view = new View('UpdatePricelist');
        $this->_pricelistManager = new PricelistManager;
        $list = $this->_pricelistManager->getPricelist($url[0], ["maxWeight", "ExpressPrice", "StandardPrice"]);

        $this->_view->generateView(['values' => $list]);
    }

    private function languages($url) {

        $this->_view = new View('Back');

        $this->_view->generateView(['name' => 'QuickBaluchon']);
    }

    public function warehouseDetails($url) {
        $this->_view = new View('warehouse');
        $this->_WarehousesManager = new WarehouseManager;
        $details = $this->_WarehousesManager->getWarehouse($url[0], ["address", "volume", 'AvailableVolume']);
        $this->_DeliveryManager = new DeliveryManager;
        $deliveryman = $this->_DeliveryManager->getDeliverys(["id"], $url[0]);

        $this->_view->generateView(["details" => $details, "id" => $url[0], "deliveryman" => count($deliveryman)]);
    }

}
