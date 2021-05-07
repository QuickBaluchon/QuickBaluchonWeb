<?php
require_once('Api.php');

class ApiDeliveryManStats extends Api
{

    private $_method;
    private $_data = [];
    private $_jwt;
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
    private $_joinTime = [
       [
           'type' => 'left',
           'table' => 'ROADMAP',
           'onT1' => 'DELIVERYMAN.id',
           'onT2' => 'ROADMAP.deliveryman'
       ]
    ] ;
    private $_joinKm = [
        [
            'type' => 'inner',
            'table' => 'ROADMAP',
            'onT1' => 'DELIVERYMAN.id',
            'onT2' => 'ROADMAP.deliveryman'
        ]
    ] ;
    private $_joinAverageTime = [
        [
            'type' => 'inner',
            'table' => 'ROADMAP',
            'onT1' => 'DELIVERYMAN.id',
            'onT2' => 'ROADMAP.deliveryman'
        ],
        [
            'type' => 'left',
            'table' => 'STOP',
            'onT1' => 'STOP.roadmap',
            'onT2' => 'ROADMAP.id'
        ]
    ] ;

    public function __construct ($url, $method) {

        $this->_method = $method;
        $this->_jwt = $this->getJwtFromHeader();
        $this->checkRole(["admin", "deliveryman"], $this->_jwt);
        $this->_data = $this->getStats($url[0], $method);

        echo json_encode($this->_data, JSON_PRETTY_PRINT);
    }

    private function getStats ($nb, $method) {

        switch ($method) {
            case 'POST': $data = $this->getJsonArray() ; break ;
            default: http_response_code(405) ; return ;
        }

        if (!isset($data['deliveryman']) && isset($_SESSION['id']))
            $data['deliveryman'] = $_SESSION['id'] ;
        elseif (!isset($data['deliveryman']) && !isset($_SESSION['id'])) {
            http_response_code(401);
            return [];
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
                        $res[] = $this->getPackagesMonth($data, $i) ;
                    break ;
                case 'heavy':
                    $res = $this->getHeavyPackages($data) ;
                    break ;
                case 'km':
                    for ($i = 0 ; $i < $nb ; ++$i)
                        $res[] = $this->getKmMonth($data, $i) ;
                    break ;
                case 'activity':
                    for ($i = 0 ; $i < $nb ; ++$i)
                        $res[] = $this->getActivityMonth($data, $i) ;
                    break ;
                case 'deliveryTime':
                    for ($i = 0 ; $i < $nb ; ++$i)
                        $res[] = $this->getDeliveryTimeMonth($data, $i) ;
                    break ;
                case 'pay':
                    $res = $this->getAveragePay($data) ;
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
        $columns = ['COUNT(PACKAGE.id) AS nb'] ;
        self::$_join = $this->_joinPackage ;

        if(isset($data["delivery"]) && $data["delivery"] == 'true')
            self::$_where[] = 'STOP.delivery IS NOT NULL' ;

        self::$_where[] = 'DELIVERYMAN.id = ?' ;
        self::$_params[] = $data['deliveryman'] ;

        if (isset($data['delivery']) && $data['delivery'] == 'true')
            self::$_where[] = 'STOP.delivery IS NOT NULL' ;

        $dt = $this->setDateClause($data, 'ROADMAP.dateRoute', $monthOffset, $yearOffset) ;

        $packages = $this->get('PACKAGE', $columns) ;
        if( count($packages) >= 1 )
            return [$dt['m'] . '-' . $dt['y'] => $packages[0]['nb']];
        else
            return [];
    }

    private function getHeavyPackages ($data) :array {
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

    private function getKmMonth ($data, $monthOffset, $yearOffset = 0) {
        $columns = ['SUM(ROADMAP.kmTotal) AS total'] ;
        self::$_join = $this->_joinKm ;

        self::$_where[] = 'DELIVERYMAN.id = ?' ;
        self::$_params[] = $data['deliveryman'] ;

        $dt = $this->setDateClause($data, 'ROADMAP.dateRoute', $monthOffset, $yearOffset) ;


        $km = $this->get('DELIVERYMAN', $columns) ;
        if( count($km) >= 1 )
            return [$dt['m'] . '-' . $dt['y'] => $km[0]['total']];
        else
            return [];
    }

    private function getActivityMonth ($data, $monthOffset, $yearOffset = 0) {
        $columns = ['AVG(ROADMAP.timeTotal) AS avg'] ;
        self::$_join = $this->_joinTime ;

        self::$_where[] = 'DELIVERYMAN.id = ?' ;
        self::$_params[] = $data['deliveryman'] ;

        $dt = $this->setDateClause($data, 'ROADMAP.dateRoute', $monthOffset, $yearOffset) ;

        $time = $this->get('DELIVERYMAN', $columns) ;
        if( count($time) >= 1 )
            return [$dt['m'] . '-' . $dt['y'] => $time[0]['avg']];
        else
            return [];
    }

    private function getDeliveryTimeMonth ($data, $monthOffset, $yearOffset = 0) {
        $columns = ['package', 'STOP.delivery'] ;
        self::$_join = $this->_joinAverageTime ;

        self::$_where[] = 'DELIVERYMAN.id = ?' ;
        self::$_params[] = $data['deliveryman'] ;

        $dt = $this->setDateClause($data, 'ROADMAP.dateRoute', $monthOffset, $yearOffset) ;

        $time = $this->get('DELIVERYMAN', $columns) ;
        if( count($time) >= 1 )
            return [$dt['m'] . '-' . $dt['y'] => $time[0]['avg']];
        else
            return [];
    }

    private function getAveragePay ($data) {
        $columns = ['AVG(netAmount) AS average'] ;

        self::$_where[] = 'deliveryman = ?' ;
        self::$_params[] = $data['deliveryman'] ;

        self::$_order = 'datePay' ;
        self::$_limit = isset($data['limit']) ? intval($data['limit']) : 3 ;

        $pay = $this->get('PAYSLIP', $columns) ;
        if (count($pay) >= 1)
            return ['average' => $pay[0]['average']] ;
        else
            return [] ;
    }

    private function setDateClause ($data, $column, $monthOffset, $yearOffset) {
        self::$_where[] = "MONTH($column) = ?" ;
        $month = (isset($data['month']) ? $data['month'] : intval(date("m"))) - $monthOffset ;
        if ($month <= 0) {
            $month = 12 + $month ;
            $yearOffset = 1 ;
        }
        self::$_params[] = $month ;

        self::$_where[] = "YEAR($column) = ?" ;
        $year = (isset($data['year']) ? $data['year'] : intval(date("Y"))) - $yearOffset ;
        self::$_params[] = $year ;
        return [
            'm' => $month,
            'y' => $year
        ] ;
    }
}
