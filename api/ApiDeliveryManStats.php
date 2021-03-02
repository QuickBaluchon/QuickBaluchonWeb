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
        $this->_data = $this->getStats($url[0], $method);

        echo json_encode($this->_data, JSON_PRETTY_PRINT);

    }

    private function getStats ($nb, $method) {

        switch ($method) {
            case 'POST': $data = $this->getJsonArray() ; break ;
            default: http_response_code(405) ; return ;
        }

        if (isset($data['stats'])) {
            $nb = !empty($nb) ? intval($nb) : 3 ;
            if ($nb > 12) {
                http_response_code(416) ;
                return ;
            }
            switch($data['stats']) {
                case 'package':
                    for ($i = 0 ; $i < $nb ; ++$i)
                        $res = $this->getPackagesMonth($data, $i) ;
                    break ;
                case 'heavy':
                    $res = $this->getHeavyPackages($data) ;
                    break ;
                default:
                    http_response_code(404) ;
                    $res = [] ;
                    break ;
            }
            return $res ;
        } else
            http_response_code(412);
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

    private function getHeavyPackages ($data) :array {
        if (!isset($data['deliveryman']) && isset($_SESSION['id']))
            $data['deliveryman'] = $_SESSION['id'] ;
        elseif (!isset($data['deliveryman']) && !isset($_SESSION['id'])) {
            http_response_code(401);
            return [];
        }

        $columns = ['PACKAGE.weight'] ;
        self::$_join = $this->_joinPackage ;

        self::$_where[] = 'PACKAGE.weight >= 30' ;

        self::$_where[] = 'DELIVERYMAN.id = ?' ;
        self::$_params[] = $data['deliveryman'] ;

        self::$_where[] = 'MONTH(STOP.delivery) = ?' ;
        self::$_params[] = isset($data['month']) ? $data['month'] : intval(date("m")) ;

        self::$_where[] = 'YEAR(STOP.delivery) = ?' ;
        self::$_params[] = isset($data['year']) ? $data['year'] : intval(date("Y")) ;

        $packages = $this->get('PACKAGE', $columns) ;
        if( count($packages) >= 1 )
            return $packages;
        else
            return [];
    }
}