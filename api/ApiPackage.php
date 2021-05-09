<?php

require_once('Api.php');

class ApiPackage extends Api {

    private $_method;
    private $_data = [];

    public function __construct($url, $method) {

        $this->_method = $method;

        if (count($url) == 0)
            $this->_data = $this->getListPackages();     // list of packages - /api/package

        elseif ( ($id = intval($url[0])) !== 0 ) {     // details one packages - /api/package/{id}
            switch ($method) {
                case 'GET': $this->_data = $this->getPackage($id);break;
                case 'PATCH': $this->_data = $this->updatePackage($id);break;
                default: $this->catError(405); break ;
            }
        } else {
            $this->_data = $this->insertPackage();
        }

        echo json_encode( $this->_data, JSON_PRETTY_PRINT );
    }

    public function getListPackages ($columns = null): array  {
        if ($columns == null)
            $columns = ['PACKAGE.id', 'client', 'ordernb', 'weight', 'volume', 'address', 'email', 'delay', 'dateDelivery', 'PACKAGE.status', 'excelPath', 'dateDeposit'];
        if(isset($_GET['inner'])) {
            $columns[] = 'PRICELIST.ExpressPrice';
            $columns[] = 'PRICELIST.StandardPrice';
            $inner = explode(',',$_GET['inner']);
            self::$_join[] = [
                'type' => 'inner',
                'table' => $inner[0],
                'onT1' => $inner[1],
                'onT2' => $inner[2]
            ] ;
        }

        if (isset($_GET['order']))
            self::$_order = $_GET['order'];

        if(isset($_GET['client'])) {
            self::$_where[] = 'client = ?';
            self::$_params[] = intval($_GET['client']);
        }

        if(isset($_GET['ordernb'])) {
            self::$_where[] = 'ordernb = ?';
            self::$_params[] = intval($_GET['ordernb']);
        }
        if(isset($_GET['date'])) {
            $date = explode('-', $_GET['date']);
            self::$_where[] = 'MONTH(dateDeposit) = ?';
            self::$_params[] = $date[1];
            self::$_where[] = 'YEAR(dateDeposit) = ?';
            self::$_params[] = $date[0];
        }
        if(isset($_GET['dateDelivery']) && $_GET['dateDelivery'] == 'now') {
            self::$_where[] = 'dateDelivery <= CURDATE()';
        }
        if(isset($_GET['status'])) {
            self::$_where[] = 'status = ?';
            self::$_params[] = intval($_GET['status']) ;
        }
        if(isset($_GET['warehouse'])) {
            self::$_where[] = 'warehouse = ?';
            self::$_params[] = intval($_GET['warehouse']) ;
        }

        $list = $this->get('PACKAGE', $columns);
        $packages = [];
        if( $list != null ){
            foreach ($list as $package) {
                //$package['url'] = API_ROOT . 'package/' . $package['id'];
                $packages[] = $package;
            }
        }
        return $packages;
    }

    public function getPackage($id): array {
        if(isset($_GET['inner'])) {
            $columns[] = 'PRICELIST.ExpressPrice, ' . 'PRICELIST.StandardPrice';
            self::$_inner = explode(',',$_GET['inner']);
        }
        $columns = ['PACKAGE.id', 'client', 'ordernb', 'weight', 'volume', 'address', 'email', 'delay', 'dateDelivery', 'PACKAGE.status', 'excelPath', 'dateDeposit', 'nameRecipient', "signature"];

        self::$_where[] = 'PACKAGE.id = ?';
        self::$_params[] = $id;
        $package = $this->get('PACKAGE', $columns);

        if( count($package) >= 1 )
            return $package[0];
        else
            return [];
    }

    public function updatePackage (int $id) {
        $data = $this->getJsonArray();
        $allowed = ['weight', 'address', 'email', 'delay', 'status', 'dateDeposit', 'dateDelivery', 'signature'];
        if( count(array_diff(array_keys($data), $allowed)) > 0 ) {
            http_response_code(400);
            exit(0);
        }

        if ($data['status'] == 3 || $data['status'] == 1) {
            if ($this->updateWarehouseVolume($id, $data['status']) != 0)
                return ;
            $this->resetParams();
        }

        foreach ($data as $key => $value) {
            self::$_set[] = "$key = ?";
            self::$_params[] = $value;
        }
        if ($data['status'] == 1) {
            self::$_set[] = "dateDeposit = now()" ;
            self::$_set[] = "dateDelivery = DATE_ADD(now(), INTERVAL ? DAY)" ;
            self::$_params[] = $data['delay'] ;

            $pricelistQuery = "SELECT id FROM PRICELIST WHERE maxWeight > ? AND applicationDate <= CURDATE() ORDER BY maxWeight ASC, applicationDate DESC LIMIT 1" ;
            self::$_set[] = "pricelist = ($pricelistQuery)" ;
            self::$_params[] = $data['weight'] ;

            if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
                http_response_code(401) ;
                return;
            }

            $warehouseQuery = "SELECT warehouse FROM STAFF WHERE id = ?" ;
            self::$_set[] = "warehouse = ($warehouseQuery)" ;
            self::$_params[] = $_SESSION['id'] ;

            self::$_set[] = 'staff = ?' ;
            self::$_params[] = $_SESSION['id'] ;
        }
        $this->patch('PACKAGE', $id);
    }

    public function updateWarehouseVolume (int $pkg, int $status) {
        if (!isset($_SESSION['warehouse'])) {
            http_response_code(401) ;
            return -1 ;
        }

        require_once ('ApiWarehouse.php') ;
        $warehouse = new ApiWarehouse (["warehouse", $_SESSION['warehouse']], "GET") ;
        $w = $warehouse->getWarehouse($_SESSION['warehouse']) ;

        $col = ['volume'];
        self::$_where[] = 'id = ?' ;
        self::$_params[] = $pkg ;
        $package = $this->get("PACKAGE", $col) ;
        if (!empty($package)) {
            $volume = $package[0]['volume'] * 0.000001 ; // cm3 to m3 conversion
        } else {
            http_response_code(404) ;
            return -1 ;
        }
        $this->resetParams();


        if ($status == 1) {
            if ($w['AvailableVolume'] - $volume < 0) {
                http_response_code(507) ;
                return -1 ;
            }
            $volume *= -1 ;
        }
        if ($status == 3) {
            if ($w['AvailableVolume'] + $volume > $w['volume'])
                $volume = $w['volume'] - $w['AvailableVolume'] ;
        }

        self::$_set[] = "AvailableVolume = AvailableVolume + ?" ;
        self::$_params[] = $volume ;

        $this->patch("WAREHOUSE", $_SESSION['warehouse']);
        return 0 ;
    }

    public function insertPackage() {
        $sql = $this->getJsonArray();
        $connect = $this->getDb();
        $stmt = $connect->prepare($sql['insert']);
        if ($stmt) {
            $success = $stmt->execute() ;
            if ($success)
                return $connect->LastInsertId() ;
        }
        return [];
    }
}
