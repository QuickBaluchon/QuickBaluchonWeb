<?php
$_SESSION["role"] = "admin";
require_once('views/View.php');

class ControllerAdmin {

    private $_adminManager;
    private $_clientManager;
    private $_WarehousesManager;
    private $_deliveryManager;
    private $_pricelistManager;
    private $_languagesManager ;
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
        else if ($url[1] === 'login') {
            $this->trylogin();
            exit();
        }
        if( !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin' ) {
            header('location:' . WEB_ROOT . 'admin');
            exit();
        }

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
                     $_SESSION['warehouse'] = $admin['warehouse'] ;
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
            'profile' => [
                'value' => 'Données personnelles',
                'color' => 'info'
            ],
            'history' => [
                'value' => 'Historique',
                'color' => 'secondary'
            ],
            'bills' => [
                'value' => 'Factures',
                'color' => 'dark',
            ]
        ];

        $rows = [];
        foreach ($list as $client) {
            foreach ($buttonsValues as $link => $inner) {
                $buttons[] = '<a href="' . WEB_ROOT . "client/$link/" . $client['id'] . '"><button type="button" class="btn btn-' . $inner['color'] . ' btn-sm">' . $inner['value'] . '</button></a>';
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

    private function staff($url) {
        $this->_view = new View('Back');
        $this->_view->_js[] = "admin/employ";
        $this->_adminManager = new AdminManager;
        $list = $this->_adminManager->getStaffs(["id", "firstname", "lastname", "username", "employed"]);
        $this->_warehouseManager = new WarehouseManager;
        $queryWarehouses = $this->_warehouseManager->getWarehouses(["id", "address"]);

        foreach ($list as $staff) {
            if($staff["employed"] == 0){
                $buttons[] = '<button type="button" class="btn btn-primary btn-sm" onclick="employStaff(' . $staff["id"] . ')">Employer</button>';
            }else if($staff["employed"] == 1){
                $buttons[] = '<button type="button" class="btn btn-danger btn-sm" onclick="refuseStaff(' . $staff["id"] . ')">Supprimer</button>';
            }
            if(isset($buttons)){
                unset($staff["employed"]);
                unset($staff["id"]);
                $rows[] = array_merge($staff, $buttons);
                $buttons = [];
            }

            else {
                unset($staff["id"]);
                unset($staff["employed"]);
                $rows[] = $staff;
            }
        }

        $warehouses = $this->_view->generateTemplate('selectWarehouses', ["warehouses" => $queryWarehouses]);
        $rows[] = [
            '<input type="text" class="form-control" id="firstname" placeholder="first name">',
            '<input type="text" class="form-control" id="lastname" placeholder="last name">',
            '<input type="text" class="form-control" id="username" placeholder="username">',
            $warehouses,
            '<button type="button" class="btn btn-success btn-sm" onclick="addStaff()">Ajouter</button>'
        ] ;

        $cols = ["lastname",'firstname', 'username', 'Action'];
        $deliveryman = $this->_view->generateTemplate('table', ['cols' => $cols, 'rows' => $rows]);
        $this->_view->generateView(['content' => $deliveryman, "name" => "QuickBalluchon", "warehouses" => $warehouses]);
    }

    private function deliverymen($url) {
        $this->_view = new View('Back');

        $this->_DeliveryManager = new DeliveryManager;
        $list = $this->_DeliveryManager->getDeliverys([], NULL);

        foreach ($list as $d) {
            $buttons[] = '<button type="button" class="btn btn-primary btn-sm" onclick="dismissDeliveryman(' . $d['id'] . ')">Licensier</button>';
            unset($d['id']);
            $rows[] = array_merge($d, $buttons);
            $buttons = [];
        }

        $cols = ['firstname', 'lastname', 'phone', 'email', 'volumeCar', 'radius', 'IBAN', 'employed', 'warehouse', 'licence', 'registration', 'dismiss'];

        $deliveryman = $this->_view->generateTemplate('table', ['cols' => $cols, 'rows' => $rows]);
        $this->_view->_js[] = 'deliveryman/dismiss';
        $this->_view->generateView(['content' => $deliveryman, 'name' => 'QuickBaluchon']);
    }

    private function warehouses($url) {
        $this->_view = new View('Back');

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
            $warehouse['active'] = $warehouse['active'] == 1 ? "&#x2713" : "&#x10102" ;
            foreach ($buttonsValues as $link => $inner) {
                $buttons[] = '<a href="' . WEB_ROOT . "admin/$link/" . $warehouse['id'] . '"><button type="button" class="btn btn-primary btn-sm">' . $inner . '</button></a>';
            }

            $rows[] = array_merge($warehouse, $buttons);
            $buttons = [];
        }

        $rows[] = [
            null,
            '<input type="text" class="form-control" id="address" placeholder="address">',
            '<input type="number" class="form-control" id="volume" placeholder="volume">',
            '<button type="button" class="btn btn-success btn-sm" onclick="addWarehouse()">Ajouter</button>'
        ] ;

        $cols = ['#', 'address', 'volume', 'AvailableVolume', 'active', 'delete'];
        if (!isset($rows)) $rows = [];
        $this->_view->_js[] = 'warehouse/updateWarehouse';
        $this->_view->_js[] = 'warehouse/addWarehouse';
        $warehouse = $this->_view->generateTemplate('table', ['cols' => $cols, 'rows' => $rows]);
        $this->_view->generateView(['content' => $warehouse, 'name' => 'QuickBaluchon']);

    }

    private function pricelist($url) {
        $this->_view = new View('Back');
        $this->_view->_js[] = 'pricelist/addPrice';
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
        $rows[] = [
            '<input type="text" class="form-control" id="maxWeight" placeholder="Max Weight">',
            '<input type="number" class="form-control" id="ExpressPrice" placeholder="Express Price">',
            '<input type="number" class="form-control" id="StandardPrice" placeholder="Standrad Price">',
            '<input type="date" class="form-control" id="applicationDate" placeholder="application Date">',
            '<input type="number" min="0" max="1" class="form-control" id="status" placeholder="status">',
            '<button type="button" class="btn btn-success btn-sm" onclick="addPrice()">Ajouter</button>'
        ] ;

        $cols = ['#', 'Max wheight', 'Express price', 'Standard price', 'application date'];
        $pricelist = $this->_view->generateTemplate('table', ['cols' => $cols, 'rows' => $rows]);
        $this->_view->generateView(['content' => $pricelist, 'name' => 'QuickBaluchon']);
    }

    private function employ($url) {

        $this->_view = new View('Back');
        $this->_view->_js[] = 'deliveryman/employ';
        $this->_deliveryManager = new DeliveryManager;
        $list = $this->_deliveryManager->getDeliveryNotEmployed(["id","firstname","lastname","phone","email","volumeCar","radius","IBAN","employed", "warehouse"]);

         $buttonsValues = [
             'employ' => 'employer',
             'refuse' => 'refuser',
         ];

        foreach ($list as $delivery) {
            foreach($buttonsValues as $link => $inner){
                $buttons[] = '<button onclick="'. $link .'('.$delivery["id"].')" id="'.$delivery["id"].'" type="button" class="btn btn-primary btn-sm">' . $inner . '</button>';
            }
            $rows[] = array_merge($delivery, $buttons);
            $buttons = [];
        }
        if(!isset($rows))
            $rows = [];

        $cols = ['id', 'firstname', 'lastname', 'phone', 'email', 'volumeCar', 'radius', 'IBAN', 'employed', 'warehouse', 'employer', 'refuser'];
        $delivery = $this->_view->generateTemplate('table', ['cols' => $cols, 'rows' => $rows]);
        $this->_view->generateView(['content' => $delivery, 'name' => 'QuickBaluchon']);
  }

    private function updatePricelist($url) {
        $this->_view = new View('back');
        $this->_pricelistManager = new PricelistManager;
        $details = $this->_pricelistManager->getPricelist($url[0], ["maxWeight", "ExpressPrice", "StandardPrice"]);
        $template =  $this->_view->generateTemplate('updatePricelist', [
            'values' => $details,
            'id'=> $url[0]
        ]);
        $this->_view->generateView(['content' => $template, 'name' => 'QuickBaluchon']);
    }

    private function languages($url) {

        $this->_view = new View('Back');
        $this->_languagesManager = new LanguagesManager ;
        $data = $this->allLanguages() ;
        $this->_view->generateView(['content' => $data, 'name' => 'QuickBaluchon']);

    }

    private function allLanguages () {
        $list = $this->_languagesManager->getLanguages() ;
        foreach ($list as $lang => $data) {
            $onclick = 'onclick="deleteLanguage(\'' . $data['shortcut'] . '\')"' ;
            $button = '<button type="button" class="btn btn-danger btn-sm"' . $onclick . '>Supprimer</button>';
            $rows[] = array_merge($data, [$button]);
        }

        $rows[] = [
            '<input type="text" class="form-control" id="shortcut" placeholder="SH">',
            '<input type="text" class="form-control" id="language" placeholder="language">',
            '<input type="text" class="form-control" id="flag" placeholder="alt-codes.net/flags">',
            '<button type="button" class="btn btn-success btn-sm" onclick="addLanguage()">Ajouter</button>'
        ] ;

        $cols = ['Shortcut', 'Language', 'Flag', 'Modify'] ;

        return $this->_view->generateTemplate('table', ['cols' => $cols, 'rows' => $rows]) ;
    }

    public function warehouseDetails ($url) {
        $this->_view = new View('Back');
        $this->_js[] = "warehouse/updateWarehouse";

        $this->_WarehousesManager = new WarehouseManager;
        $this->_DeliveryManager = new DeliveryManager;
        $details = $this->_WarehousesManager->getWarehouse($url[0], ["address", "volume", 'AvailableVolume', 'active']);
        $deliveryman = $this->_DeliveryManager->getDeliverys(["id"], $url[0]);

            $template =  $this->_view->generateTemplate('warehouse', [
                "warehouse" => $url[0],
                "details" => $details,
                "id" => $url[0], "deliveryman" => count($deliveryman)
            ]);


        $this->_view->generateView(['content' => $template, 'name' => 'QuickBaluchon']);
    }

}
