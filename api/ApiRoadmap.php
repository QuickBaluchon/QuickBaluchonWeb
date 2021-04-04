<?php


require_once('Api.php');

class ApiRoadmap extends Api
{

    private $_method;
    private $_data = [];

    public function __construct($url, $method)
    {
        $this->computeDistance('242 rue du Faubourg Saint Antoine, Paris', '114 avenue Michelet, 93400 Saint Ouen');
        $this->_method = $method;

        if (count($url) == 0) {
            switch($method) {
                case 'GET': $this->_data = $this->getListRoadmaps(); break;    // list of roadmaps - /api/roadmap
                case 'POST': $this->_data = $this->createDailyRoadmaps() ; break;
                default: $this->catError(405); break ;
            }
        }

        elseif (($id = intval($url[0])) !== 0)      // details one roadmap - /api/roadmap/{id}
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

    public function createDailyRoadmaps () {
        $warehouses = $this->getExternData('ApiWarehouse', [], 'getListWarehouse') ;
        foreach ($warehouses as $w) {
            $packages = $this->getExternData('ApiPackage', [
                'dateDelivery' => 'now',
                'status' => 2,
                'warehouse' => $w['id']
            ], 'getListPackages') ;

            $deliverymen = $this->getExternData('ApiDeliveryMan', [
                'employed' => 1,
                'warehouse' => $w['id'],
                'order' => 'radius desc'
            ], 'getListDelivery') ;

            if ($w['active'] == 1)
                $this->dispatchPackages($packages, $deliverymen) ;
        }
    }

    private function dispatchPackages ($packages, $deliverymen) {
        $avg = count($packages) / count($deliverymen);
        $d = 0;
        for (; $d < count($deliverymen) ; ++$d) {
            for($nb = 0 ; $nb < $avg ; ++$nb) {
                print_r($deliverymen[$d]);
                $packages[$d * $avg + $nb]['distance'] = random_int(1, $deliverymen[$d]['radius']);
                echo "DISTANCE " . $packages[$d * $avg + $nb]['distance'] . '<hr>' ;
            }
        }
    }

    private function computeDistance ($address1, $address2) {
        $url = $address1 . '|' . $address2;
        $url = $this->urlEncode($url);
        $url = 'https://maps.googleapis.com/maps/api/distancematrix/json?origins=' . $url;
        echo $url;

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_HEADER, FALSE);
        curl_setopt($curl, CURLOPT_POST, TRUE);

        $response = curl_exec($curl);
        curl_close($curl);
        echo $response;
    }

    private function urlEncode ($string) {
        $str = $string;
        $encodingMap = [
            ' ' => '%20',
            '"' => '%22',
            '+' => '%2B',
            ',' => '%2C',
            '<' => '%3C',
            '>' => '%3E',
            '#' => '%23',
            '%' => '%25',
            '|' => '%7C'
        ];
        foreach ($encodingMap as $character => $replacement)
            $str = str_replace($character, $replacement, $str);
        return $str;
    }

    private function createStep ($stepNb, $packageID, $roadmapID) {
        echo "$stepNb";
    }

    private function getExternData ($api, $get, $function) {
        require_once($api . '.php') ;
        $_GET = [
            'limit' => 50,
            'offset' => 0
        ] ;
        $_GET = array_merge($_GET, $get) ;

        $class = new $api([], 'GET') ;
        $i = 0 ;
        $data = [] ;
        while (!empty($rows = $class->$function())) {
            $data = array_merge($data, $rows) ;
            $_GET['offset'] = ++$i * $_GET['limit'];
        }
        return $data ;
    }

}
