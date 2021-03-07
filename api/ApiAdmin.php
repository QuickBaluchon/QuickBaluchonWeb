<?php

require_once('Api.php');

class ApiAdmin extends Api {

    private $_method;

    public function __construct($url, $method) {
        $this->_method = $method;
        if (method_exists($this, $url[0])) {
            $function = $url[0];
            $this->$function(array_slice($url, 2));
        } else
            http_response_code(404);
    }

    public function addStaff() {

        if ($this->_method != 'PUT') $this->catError(405);
        $data = $this->getJsonArray();
        $allowed = ["lastname",'firstname', 'sector', 'username', 'password'];

       if( count(array_diff(array_keys($data), $allowed)) > 0 ) {
          http_response_code(400);
          exit(0);
        }

        $password = hash("sha256", $data['password']);
        self::$_columns = ["lastname",'firstname', 'sector', 'username', 'password'];
        self::$_params = [$data['lastname'],$data['firstname'], $data['sector'], $data['username'], $password];

        $this->add('STAFF');

    }

    public function getListStaff() {

        if($this->_method != 'GET') $this->catError(405);

        self::$_where[] = 'employed = 1';


        $_columns = ["lastname",'firstname', 'sector', 'username'];
        self::$_offset = isset($_GET['offset']) ? intval($_GET['offset']) : 0;
        self::$_limit = isset($_GET['limit']) ? intval($_GET['limit']) : 20;

        $Staff = $this->get('STAFF', $_columns);

        return $Staff;


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
}
