<?php

require_once('Api.php');

class ApiDeliveryMan extends Api {

    private $_method;
    private $_data = [];


    public function __construct($url, $method) {

        $this->_method = $method;

        if (count($url) == 0)
            $this->_data = $this->getListDelivery();     // list of deliveryman - /api/deliveryman

        elseif (($id = intval($url[0])) !== 0) {    // details one deliveryman - /api/deliveryman/{id}
            switch ($method) {
                case 'GET' : $this->_data = $this->getDelivery($id); break;
                case 'PATCH' : $this->updateDelivery($id); break;
                default: http_response_code(404); exit();
            }
        } elseif (count($url) == 1) {
            switch ($url[0]) {
                case 'signup': $this->signup();break;
                case 'login': $this->login();break;
                case 'employ': $this->employ();break;
                default: http_response_code(404); exit();
            }
        }


        echo json_encode($this->_data, JSON_PRETTY_PRINT);

    }

    private function getListDelivery(): array {
        $packages = [];
        if ($this->_method != 'GET') $this->catError(405);

        if (isset($_GET['employed'])) {
            self::$_where[] = 'employed = ?';
            self::$_params[] = intval($_GET['employed']);
        }

        if (isset($_GET['warehouse'])) {
            self::$_where[] = 'warehouse = ?';
            self::$_params[] = intval($_GET['warehouse']);
        }


        $columns = ['id', 'firstname', 'lastname', 'phone', 'email', 'volumeCar', 'radius', 'IBAN', 'employed', 'warehouse', 'licenseImg', "registrationIMG"];
        self::$_offset = isset($_GET['offset']) ? intval($_GET['offset']) : 0;
        self::$_limit = isset($_GET['limit']) ? intval($_GET['limit']) : 20;

        $list = $this->get('DELIVERYMAN', $columns);

        return $list;
    }

    private function getDelivery($id): array {

        if ($this->_method != 'GET') $this->catError(405);
        $columns = ['id', 'firstname', 'lastname', 'phone', 'email', 'volumeCar', 'radius', 'IBAN', 'employed', 'warehouse', 'licenseImg', "registrationIMG"];
        self::$_where[] = 'id = ?';
        self::$_params[] = $id;
        $delivery = $this->get('DELIVERYMAN', $columns);
        if (count($delivery) == 1)
            return $delivery[0];
        else
            return [];
    }

    private function signup() {

        $data = $this->getJsonArray();
        $cols = ['firstname', 'lastname','password', 'phone', 'email', 'volumeCar', 'radius', 'IBAN', 'warehouse', 'employed'];
        for ($i = 0; $i < count($cols); $i++) {
            if (!isset($data[$cols[$i]])) {
                echo $cols[$i];
                http_response_code(400);
                exit();
            }

            if($cols[$i] == "password")
                self::$_params[] = hash('sha256', $data[$cols[$i]]);
            else
                self::$_params[] = $data[$cols[$i]];


        }


        self::$_columns = ['firstname', 'lastname', 'password','phone', 'email', 'volumeCar', 'radius', 'IBAN', 'warehouse', 'employed'];
        $this->add('DELIVERYMAN');
        //$this->login();

    }

    private function login() {
        if ($this->_method != 'POST') {
            http_response_code(405);
            exit();
        }
        $deliveryman = $this->getJsonArray();
        if ( !isset($deliveryman['name'], $deliveryman['password']) ) {
            http_response_code(400);
            exit();
        }

        self::$_columns = ['id'];
        self::$_where = ['email = ?', 'password = ?'];
        self::$_params = [$deliveryman['name'], hash('sha256', $deliveryman['password'])];

        $deliveryman = $this->get('DELIVERYMAN');
        if (count($deliveryman) == 1) {
            $id = $deliveryman[0]['id'];
            $expire = 60 * 20; // 20 min
            $role = 'deliveryman';
            $response = [
                'id' => $id,
                'role' => $role,
                'access_token' => $this->generateJWT($id, $role, $expire)
            ];

            $_SESSION['id'] = $id;
            $_SESSION['role'] = $role;
            $this->_data = $response;
        } else {
            // email/password false
            http_response_code(401);
            exit();
        }

    }

    private function updateDelivery($id) {
        $data = $this->getJsonArray();
        $allowed = ['email', 'phone', 'volumeCar', 'radius', 'password', 'oldpassword'];
        if (count(array_diff(array_keys($data), $allowed)) > 0) {
            http_response_code(400);
            exit();
        }

        // check volume >= 0.1 and radius >= 1
        if( isset($data['volumeCar']) && floatval($data['volumeCar']) < 0.1  ||
            isset($data['radius']) && intval($data['radius']) < 1) {
            http_response_code(400);
            exit();
        }

        // check if a deliveryman already has this email or if the old password is not correct
        if ( isset($data['email']) && $this->valueExists('DELIVERYMAN','email',$data['email']) ||
            isset( $data['oldpassword'] ) && !$this->isValueCorrect($id, 'DELIVERYMAN',
                'password', hash('sha256',$data['oldpassword']) )) {
            http_response_code(401);
            exit();
        } elseif( isset($data['oldpassword']) )
            unset($data['oldpassword']);

        if (isset($data['password'])) $data['password'] = hash('sha256', $data['password']);
        foreach ($data as $key => $value) {
            self::$_set[] = "$key = ?";
            self::$_params[] = $value;
        }

        $this->patch('DELIVERYMAN', $id);
    }

    public function employ(){
        $data = $this->getJsonArray();
        $allowed = ['id', 'employed'];

        if( count(array_diff(array_keys($data), $allowed)) > 0 ) {
          http_response_code(400);
          exit(0);
        }

        foreach ($data as $key => $value) {
            if($key == "id")
                $id = $value;
            else{
                self::$_set[] = "$key = ?";
                self::$_params[] = $value;
            }
        }

        $this->patch('DELIVERYMAN', $id);
    }

}
