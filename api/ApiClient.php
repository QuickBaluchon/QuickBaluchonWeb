<?php

require_once('Api.php');

class ApiClient extends Api
{

    private $_method;
    private $_data = [];

    public function __construct($url, $method)
    {

        $this->_method = $method;

        if (count($url) == 0)
            $this->_data = $this->getListClients();     // list of clients - /api/client

        elseif (($id = intval($url[0])) !== 0) { // details one client - /api/client/{id}
            switch ($method) {
                case 'GET' :
                    $this->_data = $this->getClient($id);
                    break;
                case 'PATCH' :
                    $this->updateClient($id);
                    break;
            }

        } elseif (strtolower($url[0]) === 'login')
            $this->login();

        elseif (strtolower($url[0]) === 'signup')
            $this->signup();

        elseif (strtolower($url[0]) === 'excel')
            $this->saveExcel($url);

        elseif (strtolower($url[0]) === 'signout')
            $this->signout();

        echo json_encode($this->_data, JSON_PRETTY_PRINT);

    }

    public function getListClients(): array
    {
        $clients = [];
        if ($this->_method != 'GET') $this->catError(405);

        //$this->authentication(['admin']);

        self::$_offset = isset($_GET['offset']) ? intval($_GET['offset']) : 0;
        self::$_limit = isset($_GET['limit']) ? intval($_GET['limit']) : 20;

        if (isset($_GET['name'])) {
            self::$_where[] = 'name LIKE ?';
            self::$_params[] = "%" . $_GET['name'] . "%";
        }

        $columns = ['id', 'name', 'website', 'paymentMethod'];
        $list = $this->get('CLIENT', $columns);

        if ($list != null) {
            foreach ($list as $client) {
                //$client['url'] = API_ROOT . 'client/' . $client['id'];
                $clients[] = $client;
            }
        }
        return $clients;
    }

    public function getClient($id): array
    {

        if ($this->_method != 'GET') $this->catError(405);
        //$this->authentication(['admin'], [$id]);

        $columns = ['id', 'name', 'website', 'paymentMethod'];
        self::$_where[] = 'id = ?';
        self::$_params[] = $id;
        $client = $this->get('CLIENT', $columns);
        if (count($client) == 1)
            return $client[0];
        else
            return [];
    }

    public function login() {
        if ($this->_method != 'POST') $this->catError(405);
        $client = $this->getJsonArray();
        if (isset($client['name'], $client['password'])) {
            self::$_columns = ['id'];
            self::$_where = ['name = ?', 'password = ?'];
            self::$_params = [$client['name'], hash('sha256', $client['password'])];

            $client = $this->get('CLIENT');
            if (count($client) == 1) {
                $id = $client[0]['id'];
                $expire = 3600 * 24 * 15; // 20 min
                $response = [
                    'id' => $id,
                    'role' => 'client',
                    'access_token' => $this->generateJWT($id, 'client', $expire)
                ];

                $_SESSION['id'] = $id;
                $_SESSION['role'] = 'client';
                $this->_data = $response;
            } else {
                // login/password false
                http_response_code(401);
                exit();
            }
        } else {
            // not the required parameters 'name' & 'password'
            http_response_code(400);
        }
    }

    public function signout()
    {
        if (isset($_SESSION['id'])) {
            $_SESSION = [];
        }

        if (isset($this->_data)) {
            unset($this->_data['id']);
            unset($this->_data['role']);
            unset($this->_data['access_token']);
        }

        $this->_data = WEB_ROOT;
    }

    public function signup()
    {
        $data = $this->getJsonArray();
        if (isset($data['name'], $data['website'], $data['password'])) {

            if ( $this->valueExists('CLIENT', 'name', $data['name'])  === false ) {
                extract($data);
                self::$_columns = ['name', 'website', 'password'];
                self::$_params = [$name, $website, hash('sha256', $password)];
                $this->add('CLIENT');
                $this->login();
            } else {
                http_response_code(400);
                echo 'Name already exists';
            }
        } else {
            // Bad Request : not the required parameters 'name' & 'password'
            http_response_code(400);
        }

    }

    private function saveExcel($url)
    {
        $url = array_slice($url, 1);
        $userID = $url[0] ;
        $nbPkg = $url[1] ;
        $path = 'uploads/excel/';

        if (isset($_FILES) && !empty($_FILES) && isset($url[0]) && !empty($url[0])) {

            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }

            $filepath = $path . $_FILES['excel']['name'];
            move_uploaded_file($_FILES['excel']['tmp_name'], $filepath);

            $exec = "./main $filepath $userID $nbPkg";
            exec($exec, $outputs, $rescode);
            print_r( $outputs );
            echo '  rescode: ' . $rescode ;

        } else {
            echo 'Error with the Excel file';
            echo '$_FILES:';
            var_dump($_FILES);
            echo "url[0]: $url[0] | url[1]: $url[1] ";
        }
    }

    private function updateClient($id) {
        $data = $this->getJsonArray();
        $allowed = ['name', 'website', 'password', 'oldpassword'];
        if (count(array_diff(array_keys($data), $allowed)) > 0) {
            http_response_code(400);
            exit();
        }

        // check if a user already has this name or if the old password is not correct
        if ( isset($data['name']) && $this->valueExists( 'CLIENT', 'name', $data['name']) ||
            isset( $data['oldpassword'] ) && !$this->isPwdCorrect($id, $data['oldpassword'])) {
            http_response_code(401);
            exit();
        }

        if( isset($data['oldpassword']) ) unset($data['oldpassword']);

        if (isset($data['password'])) $data['password'] = hash('sha256', $data['password']);
        foreach ($data as $key => $value) {
            self::$_set[] = "$key = ?";
            self::$_params[] = $value;
        }

        $this->patch('CLIENT', $id);

    }

    private function isPwdCorrect( $idClient, $pwd ) {
        if ($idClient != null) {
            self::$_columns = ['id'];
            self::$_where = [ 'id = ?','password = ?'];
            self::$_params = [$idClient, hash('sha256', $pwd)];
            $clients = $this->get('CLIENT');
            return count($clients) > 0;
        } else {
            return -1;
        }
    }

}
