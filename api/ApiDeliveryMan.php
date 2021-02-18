<?php

require_once('Api.php');

class ApiDeliveryMan extends Api {

    private $_method;
    private $_data = [];


    public function __construct($url, $method) {

        $this->_method = $method;


        if (count($url) == 0)
            $this->_data = $this->getListDelivery();     // list of packages - /api/deliveryman

        elseif (($id = intval($url[0])) !== 0) {    // details one packages - /api/deliveryman/{id}
            switch ($method) {
                case 'GET' :
                    $this->_data = $this->getDelivery($id);
                    break;
                case 'PATCH' :
                    $this->updateDelivery($id);
                    break;
            }
        } elseif (count($url) == 1) {
            $this->signupDeliveryman();
        }


        echo json_encode($this->_data, JSON_PRETTY_PRINT);

    }

    public function getListDelivery(): array {
        $packages = [];
        if ($this->_method != 'GET') $this->catError(405);

        if (isset($_GET['deliveryman'])) {
            self::$_where[] = 'deliveryman = ?';
            self::$_params[] = intval($_GET['deliveryman']);
        }


        $columns = ['id', 'firstname', 'lastname', 'phone', 'email', 'volumeCar', 'radius', 'IBAN', 'employed', 'wharehouse', 'licenseImg', "registrationIMG"];
        self::$_offset = isset($_GET['offset']) ? intval($_GET['offset']) : 0;
        self::$_limit = isset($_GET['limit']) ? intval($_GET['limit']) : 20;

        $list = $this->get('DELIVERYMAN', $columns);

        return $list;
    }

    public function getDelivery($id): array {

        if ($this->_method != 'GET') $this->catError(405);
        $columns = ['id', 'firstname', 'lastname', 'phone', 'email', 'volumeCar', 'radius', 'IBAN', 'employed', 'wharehouse', 'licenseImg', "registrationIMG"];
        self::$_where[] = 'id = ?';
        self::$_params[] = $id;
        $delivery = $this->get('DELIVERYMAN', $columns);
        if (count($delivery) == 1)
            return $delivery[0];
        else
            return [];
    }

    public function signupDeliveryman() {

        $data = $this->getJsonArray();
        $cols = ['firstname', 'lastname', 'phone', 'email', 'volumeCar', 'radius', 'IBAN', 'wharehouse'];
        for ($i = 0; $i < count($cols); $i++) {
            if (!isset($data[$cols[$i]])) {
                echo $cols[$i];
                http_response_code(400);
                exit();
            }
            self::$_params[] = $data[$cols[$i]];
        }


        self::$_columns = ['firstname', 'lastname', 'phone', 'email', 'volumeCar', 'radius', 'IBAN', 'wharehouse'];
        $this->add('DELIVERYMAN');
        //$this->login();

    }

    public function updateDelivery($id) {
        echo 'id: ' . $id;die();
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

}
