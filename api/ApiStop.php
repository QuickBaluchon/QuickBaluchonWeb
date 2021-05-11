<?php

require_once('Api.php');

class ApiStop extends Api {

    private $_method;
    private $_data = [];

    public function __construct($url, $method) {

        $this->_method = $method;

        if (count($url) == 0)
            $this->_data = $this->getListStops();

        elseif ( isset($url[0]) && ($pkg = intval($url[0])) !== 0 ) {     // details one packages - /api/package/{id}
            switch ($method) {
                case 'GET': $this->_data = $this->getStop($pkg);break;
                case 'PATCH': $this->_data = $this->updateStop($pkg);break;
                default: $this->_data = $this->catError(405);
            }
        } else {
            $this->_data = $this->catError(405);
        }

        echo json_encode($this->_data, JSON_PRETTY_PRINT);
    }

    protected function getListStops (): array {
        $columns = ['roadmap', 'package', 'delivery'];

        self::$_offset = isset($_GET['offset']) ? intval($_GET['offset']) : 0;
        self::$_limit = isset($_GET['limit']) ? intval($_GET['limit']) : 20;

        if (isset($_GET['package'])) {
            self::$_where[] = 'package';
            self::$_params[] = $_GET['package'];
        }

        if (isset($_GET['roadmap'])) {
            self::$_where[] = 'roadmap';
            self::$_params[] = $_GET['roadmap'];
        }

        $list = $this->get('STOP', $columns);

        if ($list != null) {
            foreach ($list as $stop) {
                $stops[] = $stop;
            }
        }
        return $stops;
    }

    public function getStop ($pkg):array {

        $columns = ['roadmap', 'package', 'delivery'];
        self::$_where[] = 'package = ?';
        self::$_params[] = $pkg;
        self::$_where[] = 'delivery = ?';
        self::$_params[] = 'NULL';
        self::$_order = 'roadmap DESC' ;
        self::$_limit = 1 ;
        $package = $this->get('STOP', $columns);
        if( count($package) == 1 )
            return $package[0];
        else
            return [];
    }

    public function updateStop ($pkg) {
        self::$_columns[] = 'MAX(roadmap) AS rdm';

        self::$_where[] = 'package = ?';
        self::$_params[] = $pkg;
        self::$_where[] = 'delivery IS NULL';
        $roadmap = $this->get('STOP');
        if (count($roadmap) != 0)
            $roadmap = $roadmap[0]['rdm'];
        else {
            http_response_code(404);
            return;
        }
        $this->resetParams();

        $sql = "UPDATE STOP SET delivery = now() WHERE package = $pkg AND roadmap = $roadmap" ;
        $stmt = $this->getDb()->prepare($sql);
        if ($stmt) {
            $success = $stmt->execute(self::$_params);
            if ($success) {
                $this->resetParams();
                http_response_code(200);
            } else {
                http_response_code(500);
            }
        } else {
            http_response_code(500);
        }

        self::$_set[] = 'currentStop = currentStop + 1';
        $this->patch('ROADMAP', $roadmap);
    }
}
