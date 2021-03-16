<?php

require_once('Api.php');

class ApiAdmin extends Api {

    private $_method;
    private $_data;
    public function __construct($url, $method) {

        $this->_method = $method;
        if (method_exists($this, $url[0])) {
            $function = $url[0];
            $this->_data = $this->$function(array_slice($url, 2));
        } else
            http_response_code(404);

        echo json_encode($this->_data, JSON_PRETTY_PRINT);
    }

    public function addStaff() {

        if ($this->_method != 'PUT') $this->catError(405);
        $data = $this->getJsonArray();
        $allowed = ["lastname",'firstname', 'username', 'warehouse'];

       if( count(array_diff(array_keys($data), $allowed)) > 0 ) {
          http_response_code(400);
          exit(0);
        }


        self::$_columns = ["lastname",'firstname', 'username', 'warehouse'];
        self::$_params = [$data['lastname'],$data['firstname'], $data['username'], $data['warehouse']];

        $this->add('STAFF');

    }

    public function getListStaff() {
        if($this->_method != 'GET') $this->catError(405);

        $columns = ["id", "lastname", "firstname", "sector", "username", "employed"];
        $list = $this->get('STAFF', $columns);

        return $list;
    }

    public function login() {
        if ($this->_method != 'POST') $this->catError(405);
        $admin = $this->getJsonArray();
        if (isset($admin['username'], $admin['password'])) {
            self::$_columns = ['id'];
            self::$_where = ['username = ?', 'password = ?'];
            self::$_params = [$admin['username'], hash('sha256', $admin['password'])];

            $admin = $this->get('STAFF');
            if (count($admin) == 1) {
                $id = $admin[0]['id'];
                $expire = 60 * 20; // 20 min
                $response = [
                    'id' => $id,
                    'role' => 'admin',
                    'access_token' => $this->generateJWT($id, 'admin', $expire)
                ];
                echo json_encode($response, JSON_PRETTY_PRINT);
                exit;
            } else {
                // username/password false
                http_response_code(401);
                exit();
            }
        } else {
            // not the required parameters 'username' & 'password'
            http_response_code(400);
        }
    }

    private function updateStaff() {
        $data = $this->getJsonArray();
        $allowed = ["id", "employed"];
        if (count(array_diff(array_keys($data), $allowed)) > 0) {
            http_response_code(400);
            exit();
        }
        if(isset($data["id"]) && isset($data["employed"])){
            self::$_set[] = "employed = ?";
            self::$_params[] = $data["employed"];
        }

        $this->patch('STAFF', $data["id"]);
    }

}
