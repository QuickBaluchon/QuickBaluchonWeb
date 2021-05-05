<?php

require_once('Api.php');

class ApiWarehouse extends Api {

    private $_method;
    private $_data = [];


    public function __construct($url, $method) {

        $this->_method = $method;

        if (count($url) == 0){
            switch ($method) {
                case 'GET': $this->_data = $this->getListWarehouse();break;
                case 'POST': $this->addWarehouse();
            }
        }     // list of packages - /api/warehouse

        elseif ( ($id = intval($url[0])) !== 0 ){// details one packages - /api/warehouse/{id}
            switch ($method) {
                case 'GET': $this->_data = $this->getWarehouse($id);break;
                case 'DELETE': $this->deleteWarehouse($id);break;
                case 'PATCH': $this->patchWarehouse($id);break;
                default: $this->catError(405) ; break ;
            }
        }
        echo json_encode( $this->_data, JSON_PRETTY_PRINT );
    }

    public function getListWarehouse(): array  {
        $packages = [];

        $columns = ['id', 'address', 'volume', 'AvailableVolume', 'active'];
        self::$_offset = isset($_GET['offset']) ? intval($_GET['offset']) : 0;
        self::$_limit = isset($_GET['limit']) ? intval($_GET['limit']) : 20;
        $list = $this->get('WAREHOUSE', $columns);

        return $list;
    }

    public function getWarehouse($id): array {

        if($this->_method != 'GET') $this->catError(405);
        //$this->authentication(['admin'], [$id]);
        self::$_columns = ['id', 'address', 'volume', 'AvailableVolume', 'active'];
        self::$_where[] = 'id = ?';
        self::$_params[] = $id;
        $warehouse = $this->get('WAREHOUSE');
        if( count($warehouse) == 1 )
            return $warehouse[0];
        else
            return [];
    }

    private function deleteWarehouse($id){

        $data = $this->getJsonArray();
        $allowed = ['active'];
        if( count(array_diff(array_keys($data), $allowed)) > 0 ) {
            http_response_code(400);
            exit(0);
        }

        foreach ($data as $key => $value) {
            self::$_set[] = "$key = ?";
            self::$_params[] = $value;
        }


        $this->patch("WAREHOUSE", $id);
    }


    private function patchWarehouse (int $id) {

        $data = $this->getJsonArray();
        $allowed = ['volume', 'AvailableVolume', 'active', 'address'];
        if( count(array_diff(array_keys($data), $allowed)) > 0 ) {
            http_response_code(400);
            exit(0);
        }

        if (isset($data['AvailableVolume'])) {
            self::$_set[] = "AvailableVolume = ?";
            self::$_params[] = $data['AvailableVolume'];
        }
        if (isset($data['volume'])) {
            self::$_set[] = "volume = ?";
            self::$_params[] = $data['volume'];
        }
        if (isset($data['active'])) {
            self::$_set[] = "active = ?" ;
            self::$_params[] = $data['active'] ;
        }
        if (isset($data['address'])) {
            self::$_set[] = "address = ?" ;
            self::$_params[] = $data['address'] ;
        }

        $this->patch("WAREHOUSE", $id);
    }

    private function addWarehouse(){

        $data = $this->getJsonArray();
        $allowed = ['address', 'volume'];
        if ($data == null)
            return;
        if( count(array_diff(array_keys($data), $allowed)) > 0 ) {
            http_response_code(400);
            exit(0);
        }
        self::$_columns = $allowed;
        self::$_params = array_values($data);

        $this->add("WAREHOUSE", );
    }
}
