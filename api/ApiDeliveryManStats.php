<?php
require_once('Api.php');

class ApiDeliveryManStats extends Api
{

    private $_method;
    private $_data = [];
    private $_joinPackage = [
        [
            'type' => 'inner',
            'table' => 'STOP',
            'onT1' => 'STOP.package',
            'onT2' => 'PACKAGE.id'
        ],
        [
            'type' => 'left',
            'table' => 'ROADMAP',
            'onT1' => 'ROADMAP.id',
            'onT2' => 'STOP.roadmap'
        ],
        [
            'type' => 'left',
            'table' => 'DELIVERYMAN',
            'onT1' => 'DELIVERYMAN.id',
            'onT2' => 'ROADMAP.deliveryman'
        ]
    ] ;

    public function __construct ($url, $method) {

        $this->_method = $method;

        switch ($method) {
            case 'GET':
                $this->_data = $this->getStats($url[0]);
                break;
        }

        echo json_encode($this->_data, JSON_PRETTY_PRINT);

    }

    private function getStats ($nb) {
        $data = $this->getJsonArray() ;
        if (isset($data['stats'])) {
            $nb = !empty($nb) ? intval($nb) : 3 ;
            switch($data['stats']) {
                case 'package':
                    for ($i = 0 ; $i < $nb ; ++$i)
                        $data[] = $this->getPackagesMonth($data, $i) ;
                    break ;
            }
            return $data ;
        } else
            http_response_code(400);
    }

    private function getPackagesMonth ($data, $monthOffset, $yearOffset = 0) :array {
        if (!isset($data['deliveryman']) && isset($_SESSION['id']))
            $data['deliveryman'] = $_SESSION['id'] ;
        elseif (!isset($data['deliveryman']) && !isset($_SESSION['id'])) {
            http_response_code(401);
            return [];
        }

        $columns = ['COUNT(PACKAGE.id) AS nb'] ;
        self::$_join = $this->_joinPackage ;

        self::$_where[] = 'DELIVERYMAN.id = ?' ;
        self::$_params[] = $data['deliveryman'] ;

        self::$_where[] = 'MONTH(STOP.delivery) = ?' ;
        $month = (isset($data['month']) ? $data['month'] : intval(date("m"))) - $monthOffset ;
        if ($month <= 0) {
            $month = 12 + $month ;
            $yearOffset = 1 ;
        }
        self::$_params[] = $month ;

        self::$_where[] = 'YEAR(STOP.delivery) = ?' ;
        $year = (isset($data['year']) ? $data['year'] : intval(date("Y"))) - $yearOffset ;
        self::$_params[] = $year ;

        $packages = $this->get('PACKAGE', $columns) ;
        if( count($packages) >= 1 )
            return [$month . '-' . $year => $packages[0]['nb']];
        else
            return [];
    }
}