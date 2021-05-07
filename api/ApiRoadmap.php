<?php


require_once('Api.php');

class ApiRoadmap extends Api
{

    private $_method;
    private $_data = [];
    private $_maxDistanceByDeliveryman = 200;
    private $_maxTimeByDeliveryman = 4;
    private $_apiKey = 'AIzaSyC-zvNlWXpliMZE78i21qC5abdjGenv6AQ';
    private $_mailDelivered = "Bonjour, votre colis vous sera livré aujourd'hui ! Cordialement, l'équipe QuickBaluchon";

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

        elseif (($id = intval($url[0])) !== 0) {     // details one roadmap - /api/roadmap/{id}
            if ($method == 'GET' || $method == 'PATCH' || $method == 'DELETE') {
                $method = strtolower($method) . 'Roadmap';
                $this->_data = $this->$method($id);
            } else
               $this->catError(405);

        }
        else {
            $this->_data = $this->getRoadmap(null);
        }


        echo json_encode($this->_data, JSON_PRETTY_PRINT);
    }

    public function getListRoadmaps(): array {
        if($this->_method != 'GET') $this->catError(405);

        //$this->authentication(['admin']);
        if (!isset($_GET['fields']))
            $columns = ['ROADMAP.id', 'kmTotal', 'timeTotal', 'nbPackages', 'currentStop', 'dateRoute', 'deliveryman'];
        else
            $columns = explode(',', $_GET['fields']);
        if(isset($_GET['inner'])) {
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

        self::$_order = 'dateRoute DESC';

        return $this->get('ROADMAP', $columns);
    }

    /*
     * SELECT ROADMAP.dateRoute, ROADMAP.deliveryman, STOP.roadmap, STOP.package, PACKAGE.address, STOP.step, STOP.timeNextHop, STOP.distanceNextHop FROM ROADMAP
     * LEFT JOIN STOP ON STOP.roadmap = ROADMAP.id
     * INNER JOIN PACKAGE ON PACKAGE.id = STOP.package
     * ORDER BY STOP.roadmap, STOP.step;
     */
    public function getRoadmap (?int $id): array {
        //$this->authentication(['admin'], [$id]);
        if (isset($id)) {
            self::$_where[] = 'ROADMAP.id = ?';
            self::$_params[] = $id;
        } else {
            if (isset($_GET['date'])) {
                self::$_where[] = 'dateRoute = ?';
                self::$_params[] = $_GET['date'];
            }
            if (isset($_GET['deliveryman'])) {
                self::$_where[] = 'deliveryman = ?';
                self::$_params[] = $_GET['deliveryman'];
            }
        }
        if (!isset($_GET['fields']))
            $columns = ['ROADMAP.id', 'STOP.package', 'step', 'delivery', 'address', 'email', 'nameRecipient', 'timeNextHop', 'distanceNextHop', 'kmTotal', 'timeTotal', 'nbPackages', 'currentStop', 'dateRoute', 'deliveryman', 'finished'];
        else
            $columns = explode(',', $_GET['fields']);
        self::$_join[] = [
            'type' => 'LEFT',
            'table' => 'STOP',
            'onT1' => 'ROADMAP.id',
            'onT2' => 'STOP.roadmap'
        ];
        self::$_join[] = [
            'type' => 'INNER',
            'table' => 'PACKAGE',
            'onT1' => 'PACKAGE.id',
            'onT2' => 'STOP.package'
            ];

        self::$_order = 'step';

        $roadmaps = $this->get('ROADMAP', $columns);

        if (count($roadmaps) > 0)
            return $this->rewriteRoadmap($roadmaps);
        else
            return [];
    }

    private function rewriteRoadmap (array $roadmap) {
        $stops = [];
        foreach ($roadmap as $stop) {
            $stops[$stop['step']] = [
                'package' => $stop['package'],
                'address' => $stop['address'],
                'recipient' => $stop['nameRecipient'],
                'email' => $stop['email'],
                'timeNextHop' => $stop['timeNextHop'],
                'distanceNextHop' => $stop['distanceNextHop'],
                'delivery' => $stop['delivery']
            ];
        }

        return [
            'id' => $roadmap[0]['id'],
            'dateRoute' => $roadmap[0]['dateRoute'],
            'deliveryman' => $roadmap[0]['deliveryman'],
            'finished' => $roadmap[0]['finished'],
            'kmTotal' => $roadmap[0]['kmTotal'],
            'timeTotal' => $roadmap[0]['timeTotal'],
            'currentStop' => $roadmap[0]['currentStop'],
            'stops' => $stops
        ];
    }

    public function deleteRoadmap (int $id) {
        $roadmap = $this->getRoadmap($id);

        if ($roadmap == null) {
            http_response_code(404) ;
            return ;
        }

        $kmNotDone = 0.0;
        $timeNotDone = 0.0;
        for ($i = $roadmap['currentStop'] ; $i < count($roadmap['stops']) ; ++$i) {
            $kmNotDone += $roadmap['stops'][$i]['distanceNextHop'] ;
            $timeNotDone += $roadmap['stops'][$i]['timeNextHop'] ;
            $this->resetParams();
            self::$_set[] = 'status = ?';
            self::$_params[] = 1;
            $this->patch('PACKAGE', $roadmap['stops'][$i]['package']);
        }

        $this->resetParams();
        $this->patchRoadmap($id, [
            'kmTotal' => $roadmap['kmTotal'] - $kmNotDone,
            'timeTotal' => $roadmap['timeTotal'] - $timeNotDone,
            'finished' => 1
        ]);

        return [] ;
    }

    public function patchRoadmap ($roadmapID, ?array $data = null) {
        if ($data == null)
            $data = $this->getJsonArray();

        $allowed = ['kmTotal', 'timeTotal', 'currentStop', 'finished'];
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

    public function createDailyRoadmaps () {
        $warehouses = $this->getExternData('ApiWarehouse', [], 'getListWarehouse') ;
        foreach ($warehouses as $w) {
            $packages = $this->getExternData('ApiPackage', [
                'dateDelivery' => 'now',
                'status' => 1,
                'warehouse' => $w['id'],
                'order' => 'dateDelivery'
            ], 'getListPackages') ;

            $deliverymen = $this->getExternData('ApiDeliveryman', [
                'employed' => 1,
                'warehouse' => $w['id'],
                'order' => 'radius desc'
            ], 'getListDelivery') ;

            echo "START DISPATCHING // ";

            if ($w['active'] == 1) {
                $dailyRoadmaps = $this->dispatchPackages($packages, $deliverymen, $w['address']);

                echo "ROADMAPS FOR WAREHOUSE " . $w['id'] . " // ";
                var_dump($dailyRoadmaps);

                foreach ($dailyRoadmaps as $r) {
                    if ($r['roadDistance'] != 0) {
                        $id = $this->insertRoadmapDB($r);
                        $this->createSteps($id, $r['packages']);
                    }
                }
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
        require_once('ApiMail.php');
        return $this->recursiveRoadmaps($roadmaps, 0, $packages, $nbDeliverymen, $warehouseAddress) ;
    }

    private function recursiveRoadmaps (array $roadmaps, int $iter, array $packages, int $nbDeliverymen, string $warehouseAddress) :array {
        if ($iter > 2) return $roadmaps;

        $count = 0 ;
        $countGoogle = 0;
        $r = 0;
        for ($p = 0 ; $p < count($packages) ; ++$p) {
            var_dump($p);
            if (!isset($packages[$p]['isInRoadmap'])) {
                $dtFromWarehouse = $this->computeRoute([$warehouseAddress], [$packages[$p]['address']]);
                if ($dtFromWarehouse != null) {
                    $roadTime = $dtFromWarehouse[0][0]['time'];
                    $roadDistance = $dtFromWarehouse[0][0]['distance'];
                    $volumeM3 = $packages[$p]['volume'] / 1000000;

                    if ($roadDistance < $roadmaps[$r]['deliveryRadius'] &&
                        $roadmaps[$r]['availableVolume'] - $volumeM3 >= 0 &&
                        $roadmaps[$r]['roadTime'] + 2 * $roadTime < $this->_maxTimeByDeliveryman &&
                        $roadmaps[$r]['roadDistance'] + 2 * $roadDistance < $this->_maxDistanceByDeliveryman)
                    {
                        $roadmaps[$r]['availableVolume'] -= $volumeM3;
                        $roadmaps[$r]['roadTime'] += 2 * $roadTime;
                        $roadmaps[$r]['roadDistance'] += 2 * $roadDistance;
                        $roadmaps[$r]['packages'][] = $packages[$p];
                        $mail = new ApiMail($packages[$p]['email'], "Livraison de votre colis", $this->_mailDelivered);
                        $packages[$p]['isInRoadmap'] = true;
                    }
                }
                $countGoogle++;
                $r = $r + 1 >= $nbDeliverymen ? ($r + 1) % $nbDeliverymen : $r + 1;
            }
            $count++;
        }
        echo "COUNT GOOGLE $countGoogle / COUNT ITER $count // ";
        $roadmaps = $this->recursiveRoadmaps($roadmaps, $iter + 1, $packages, $nbDeliverymen, $warehouseAddress);
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
            self::$_columns = ['roadmap', 'package', 'step', 'timeNextHop', 'distanceNextHop'];
            self::$_params = [
                $roadmapID,
                $pkg['id'],
                $i,
                $pkg['timeNextHop'],
                $pkg['distanceNextHop']
            ];
            $this->add('STOP');
            $this->resetParams();
            self::$_set[] = 'status = ?';
            self::$_params[] = 2;
            $this->patch('PACKAGE', $pkg['id']);
            $i++;
        }
        $this->resetParams();
    }

    //to do if opti
    private function sortPackagesByDistance (array $packages) {
        $count = 0 ;
        for ($i = 0 ; $i < count($packages) ; ++$i) {
            $destinations = [];
            for ($j = $i + 1 ; $j < count($packages) ; ++$j)
                $destinations[] = $packages[$j]['address'];

            if ($destinations != null) {
                $routes = $this->computeRoute([$packages[$i]['address']], $destinations);
                echo "ROUTES SORTING // ";
                var_dump($routes);
                $closestStopIndex = $this->findClosestStop($routes[0]);

                //Switching order of packages if necessary
                if ($closestStopIndex != 0) {
                    $tmp = $packages[$i + 1];
                    $packages[$i + 1] = $packages[$closestStopIndex];
                    $packages[$closestStopIndex] = $tmp;
                }
                $packages[$i]['timeNextHop'] = $routes[0][$closestStopIndex]['time'];
                $packages[$i]['distanceNextHop'] = $routes[0][$closestStopIndex]['distance'];
            } else {
                $packages[$i]['timeNextHop'] = 0.0;
                $packages[$i]['distanceNextHop'] = 0.0;
            }
            $count ++;
            echo "PACKAGES SORTING // ";

        }
        var_dump($packages);
        echo "TOTAL ITER FOR SORTING : $count / " . count($packages) ;
        return $packages;
    }

    private function findClosestStop (array $routes) :int {
        $min = count($routes);
        $minRoute = $this->_maxDistanceByDeliveryman;
        foreach ($routes as $index => $destination) {
            if ($destination['distance'] < $minRoute) {
                $min = $index;
                $minRoute = $destination['distance'];
            }
        }
        return $min;
    }

    private function computeRoute (array $origins, array $destinations) :array {
        $googleData = $this->curlGoogle($origins, $destinations);

        $distanceAndTime = [];
        $o = 0;
        if ($googleData != null) {
            foreach ($googleData as $elements) {
                $d = 0;
                foreach ($elements as $element) {
                    foreach ($element as $road) {
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
                }
                $o++;
            }
        }
        echo "DISTANCE AND TIME";
        var_dump($distanceAndTime);
        return $distanceAndTime;
    }

    private function curlGoogle (array $origins, array $destinations) :array {
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
            '%' => '%25'
            //'|' => '%7C' can't be encoded to google API otherwise multi-origin/destination requests don't work
        ];
        foreach ($encodingMap as $character => $replacement)
            $str = str_replace($character, $replacement, $str);
        return $str;
    }

}
