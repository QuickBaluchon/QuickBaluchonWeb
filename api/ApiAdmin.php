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
