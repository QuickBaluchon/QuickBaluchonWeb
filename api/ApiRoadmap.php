<?php


require_once('Api.php');

class ApiRoadmap extends Api
{

    private $_method;
    private $_data = [];

    public function __construct($url, $method)
    {

        $this->_method = $method;

        if (count($url) == 0)
            $this->_data = $this->getListRoadmaps();     // list of bills - /api/bill

        elseif (($id = intval($url[0])) !== 0)      // details one bills - /api/bill/{id}
            switch ($method) {
                case 'GET':$this->_data = $this->getRoadmap($id);break;
                case 'PATCH': $this->_data = $this->cancelRound($url); break ;
                default: $this->catError(405); break ;
            }


        echo json_encode($this->_data, JSON_PRETTY_PRINT);

    }

    public function getListRoadmaps(): array {
        if($this->_method != 'GET') $this->catError(405);

        //$this->authentication(['admin']);
        $columns = ['ROADMAP.id', 'kmTotal', 'timeTotal', 'nbPackages', 'currentStop', 'dateRoute', 'deliveryman'];
        if(isset($_GET['inner'])) {
            $columns[] = 'ROADMAP.kmTotal';
            $inner = explode(',',$_GET['inner']);
            self::$_join[] = [
                'type' => 'inner',
                'table' => $inner[0],
                'onT1' => $inner[1],
                'onT2' => $inner[2]
            ] ;
        }

        if(isset($_GET['client'])) {
            self::$_where[] = 'deliveryman = ?';
            self::$_params[] = intval($_GET['client']);
        }

        if(isset($_GET['date'])) {
            $date = explode('-', $_GET['date']);
            self::$_where[] = 'MONTH(dateRoute) = ?';
            self::$_params[] = $date[1];
        }

        $list = $this->get('ROADMAP', $columns);
        $bills = [];
        if( $list != null ){
            foreach ($list as $bill) {
                $bills[] = $bill;
            }
        }
        return $bills;
    }

    public function getRoadmap($id): array {
        //$this->authentication(['admin'], [$id]);
        self::$_where[] = 'id = ?';
        self::$_params[] = $id;
        $columns = ['id', 'kmTotal', 'timeTotal', 'nbPackages', 'currentStop', 'dateRoute', 'deliveryman'];
        $client = $this->get('ROADMAP', $columns);
        if (count($client) == 1)
            return $client[0];
        else
            return [];
    }

    public function cancelRound (array $url) {
        $pkg = $url[0] ;
        $date = isset($url[1]) ? $url[1] : null ;
        $roadmapID = $this->getTodayRoadmapFromPkgDate($pkg, $date) ;
        $this->resetParams();

        if (empty($roadmapID)) {
            http_response_code(404) ;
            return ;
        } else
            $roadmapID = $roadmapID[0]['id'] ;

        //get sum of km by for undelivered packages
        //update the roadmap

        return [] ;
    }

    public function getTodayRoadmapFromPkgDate (int $pkg, ?string $date) :array {
        $columns = ['id'] ;
        if (isset($date) && !empty($date)) {
            self::$_where[] = "dateRoute = ?" ;
            self::$_params[] = "STR_TO_DATE($date, %d-%m-%Y)" ;
        } else {
            self::$_where[] = "dateRoute = CURDATE()" ;
        }

        self::$_where[] = "package = ?" ;
        self::$_params[] = $pkg ;

        self::$_join[] = [
            'type' => 'INNER',
            'table' => 'STOP',
            'onT1' => 'STOP.roadmap',
            'onT2' => 'ROADMAP.id'
        ] ;

        return $this->get('ROADMAP', $columns) ;
    }
}
