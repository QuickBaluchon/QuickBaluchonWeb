<?php


require_once('Api.php');

class ApiRoadmap extends Api
{

    private $_method;
    private $_data = [];
    private $_maxDistanceByDeliveryman = 200;
    private $_maxTimeByDeliveryman = 4;
    private $_apiKey = 'AIzaSyC-zvNlWXpliMZE78i21qC5abdjGenv6AQ';

    public function __construct($url, $method)
    {
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
                case 'PATCH': $this->_data = $this->updateRoadmap($id); break ;
                case 'DELETE': $this->_data = $this->cancelRound($url); break ;
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
        $roadmap = $this->getTodayRoadmapFromPkgDate($pkg, $date) ;

        if ($roadmap == null) {
            http_response_code(404) ;
            return ;
        }

        //get sum of km by for undelivered packages
        //update the roadmap

        return [] ;
    }

    public function updateRoadmap ($roadmapID, ?array $data = null) {
        if ($data == null)
            $data = $this->getJsonArray();

        $allowed = ['kmTotal', 'timeTotal', 'currentStop', 'finished', 'timeNextHop', 'distanceNextHop'];
        if( count(array_diff(array_keys($data), $allowed)) > 0 ) {
            http_response_code(400);
            exit(0);
        }

        foreach ($data as $key => $value) {
            self::$_set[] = "$key = ?";
            self::$_params[] = $value;
        }

        $this->patch('ROADMAP', $roadmapID);
        $this->resetParams();
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

        $roadmap = $this->get('ROADMAP', $columns) ;
        $this->resetParams();
        return $roadmap;
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
                'order' => 'dateDelivery, radius desc'
            ], 'getListDelivery') ;

            echo "START DISPATCHING // ";

            if ($w['active'] == 1) {
                $dailyRoadmaps = $this->dispatchPackages($packages, $deliverymen, $w['address']);

                /*$dailyRoadmaps[] = [
                        "deliverymanID" => "4",
                        "availableVolume" => 799.79932,
                        "deliveryRadius" => "50",
                        "roadTime" => 4.7672222222222,
                        "roadDistance" => 245.094,
                        "packages" => ["4","6","9","11","15","17","19","21","24","26","28","30"]
                    ];
                $dailyRoadmaps[] = [
                        "deliverymanID" => "18",
                        "availableVolume" => 1.826,
                        "deliveryRadius" => "5",
                        "roadTime" => 1.1177777777778,
                        "roadDistance" => 18.732,
                        "packages" => ["5","7","10","12","16","18","20","22"]
                    ];*/

                echo "ROADMAPS // ";
                var_dump($dailyRoadmaps);

                /*foreach ($dailyRoadmaps as $r) {
                    $id = $this->insertRoadmapDB($r);
                    $this->createSteps($id, $r['packages']);
                }*/
            }
        }
    }

    private function dispatchPackages (array $packages, array $deliverymen, string $warehouseAddress) :array {
        $nbDeliverymen = count($deliverymen);
        $nbPackages = count($packages);
        $roadmaps = [];
        foreach ($deliverymen as $d) {
            $roadmaps[] = [
                'deliverymanID' => $d['id'],
                'availableVolume' => $d['volumeCar'],
                'deliveryRadius' => $d['radius'],
                'roadTime' => 0.0,
                'roadDistance' => 0.0,
                'packages' => []
            ];
        }
        return $this->recursiveRoadmaps($roadmaps, 0, $packages, $nbDeliverymen, $nbPackages, $warehouseAddress) ;
    }

    private function recursiveRoadmaps (array $roadmaps, int $iter, array $packages, int $nbDeliverymen, int $nbPackages, string $warehouseAddress) :array {
        if ($iter > 2 || count($packages) == 0)
            return $roadmaps;
        $count = 0 ;
        $countGoogle = 0;
        $r = 0;
        for ($p = 0 ; $p < $nbPackages ; ++$p) {
            if (isset($packages[$p])) {
                $dtFromWarehouse = $this->computeDistance([$warehouseAddress], [$packages[$p]['address']]);
                if ($dtFromWarehouse != null) {
                    $volumeM3 = $packages[$p]['volume'] / 1000;
                    if ($dtFromWarehouse['distance'] < $roadmaps[$r]['deliveryRadius'] &&
                        $roadmaps[$r]['availableVolume'] - $volumeM3 >= 0 &&
                        $roadmaps[$r]['roadTime'] < $this->_maxTimeByDeliveryman &&
                        $roadmaps[$r]['roadDistance'] < $this->_maxDistanceByDeliveryman)
                    {
                        $roadmaps[$r]['availableVolume'] -= $volumeM3;
                        $roadmaps[$r]['roadTime'] += 2 * $dtFromWarehouse['time'];
                        $roadmaps[$r]['roadDistance'] += 2 * $dtFromWarehouse['distance'];
                        $roadmaps[$r]['packages'][] = $packages[$p];
                        //mail to recipient
                        unset($packages[$p]);
                    }
                }
                $countGoogle++;
                $r = $r + 1 >= $nbDeliverymen ? ($r + 1) % $nbDeliverymen : $r + 1;
            }
            $count++;
        }
        echo "COUNT GOOGLE $countGoogle / COUNT ITER $count // ";
        $roadmaps = $this->recursiveRoadmaps($roadmaps, $iter + 1, $packages, $nbDeliverymen, $nbPackages, $warehouseAddress);
        return $roadmaps;
    }

    private function insertRoadmapDB (array $r) :int {
        self::$_columns = ['kmTotal', 'timeTotal', 'nbPackages', 'currentStop', 'deliveryman', 'dateRoute', 'finished'];
        self::$_params = [
            $r['roadDistance'],
            $r['roadTime'],
            count($r['packages']),
            0,
            $r['deliverymanID'],
            date("Y-m-d"),
            0
        ];
        $id = $this->add('ROADMAP') ;
        $this->resetParams();
        return $id;
    }

    private function createSteps (int $roadmapID, array $packages) {
        $i = 0;

        $packages = $this->sortPackagesByDistance($packages);
        foreach ($packages as $pkg) {
            self::$_columns = ['roadmap', 'package', 'step'];
            self::$_params = [
                $roadmapID,
                $pkg['id'],
                $i
            ];
            $this->add('STOP');
            $i++;
        }
        $this->resetParams();
    }

    //to do if opti
    private function sortPackagesByDistance (array $packages) {
        $count = 0 ;
        for ($i = 0 ; $i < count($packages) - 1 ; ++$i) {
            $intervert = 0;
            $minDist = $this->_maxDistanceByDeliveryman;
            $timeNextHop = 0.0;
            $distanceNextHop = 0.0;
            for ($j = $i + 1 ; $j < count($packages) ; ++$j) {
                $count ++;
                echo "Computing distance from " . $packages[$i]['address'] . ' to ' . $packages[$j]['address'];
                /*$distAndTime = $this->computeDistance($packages[$i]['address'], $packages[$j]['address']);
                if ($distAndTime['distance'] < $minDist) {
                    $timeNextHop = $distAndTime['time'];
                    $distanceNextHop = $distAndTime['distance'];
                    $intervert = $j;
                }*/
            }
            if ($intervert != 0) {
                $tmp = $packages[$i+1];
                $packages[$i+1] = $packages[$intervert] ;
                $packages[$intervert] = $tmp;
            }
        }
        echo "TOTAL ITER FOR SORTING : $count / " . count($packages) ;
        return $packages;
    }

    private function computeDistance (array $origins, array $destinations) :array {
        $googleData = $this->curlGoogle($origins, $destinations);

        $distanceAndTime = [];
        $o = 0;
        if ($googleData != null) {
            foreach ($googleData as $elements) {
                $d = 0;
                foreach ($elements as $road) {
                    if ($road['status'] == 'OK') {
                        $distanceAndTime[$o][] = [
                            'origin' => $origins[$o],
                            'destination' => $destinations[$d],
                            'distance' => $road['distance']['value'] / 1000,
                            'time' => $road['duration']['value'] / 3600
                        ];
                    }
                    $d++;
                }
                $o++;
            }
        }
        return $distanceAndTime;
    }

    private function curlGoogle (array $origins, array $destinations) :array {
        var_dump( $origins);
        var_dump( $destinations);
        $origins = $this->urlEncode(join('|', $origins));
        $destinations = $this->urlEncode(join('|', $destinations));
        $params = $this->urlEncode('origins=' . $origins . '&destinations=' . $destinations);
        $url = 'https://maps.googleapis.com/maps/api/distancematrix/json?' . $params;

        $url .= '&key=' . $this->_apiKey;

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_HEADER, FALSE);
        curl_setopt($curl, CURLOPT_POST, TRUE);

        $googleResponse = curl_exec($curl);
        curl_close($curl);
        $googleResponse = json_decode($googleResponse, TRUE);

        if ($googleResponse['status'] != 'OK')
            return [];
        return $googleResponse['rows'];
    }

    private function urlEncode (string $string) :string {
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

    private function getExternData (string $api, array $get, $function) :array {
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
