<?php

require_once('Api.php');

class ApiDeliveryMan extends Api {

    private $_method;
    private $_data = [];
    private $_jwt;


    public function __construct($url, $method) {

        $this->_method = $method;
        $this->_jwt = $this->getJwtFromHeader();
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
                case 'register': $this->registerFile();break;
                default: http_response_code(404); exit();
            }
        }

        echo json_encode($this->_data, JSON_PRETTY_PRINT);
    }

    public function getListDelivery(): array {
        $this->checkRole(["admin", "deliveryman"], $this->_jwt);
        if ($this->_method != 'GET') $this->catError(405);

        if (isset($_GET['employed'])) {
            self::$_where[] = 'employed = ?';
            self::$_params[] = intval($_GET['employed']);
        }

        if (isset($_GET['warehouse'])) {
            self::$_where[] = 'warehouse = ?';
            self::$_params[] = intval($_GET['warehouse']);
        }

        $columns = ['id', 'firstname', 'lastname', 'phone', 'email', 'volumeCar', 'radius', 'IBAN', 'employed', 'warehouse', 'licenseImg', "registrationIMG", 'employStart', 'employEnd'];
        self::$_offset = isset($_GET['offset']) ? intval($_GET['offset']) : 0;
        self::$_limit = isset($_GET['limit']) ? intval($_GET['limit']) : 20;

        $list = $this->get('DELIVERYMAN', $columns);

        return $list;
    }

    public function getDelivery($id): array {
        if ($this->_method != 'GET') $this->catError(405);
        //$this->checkRole(["admin", "deliveryman"], $this->_jwt);

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
        $id = $this->add('DELIVERYMAN');
        $this->_data = ["id" => $id];
        //$this->login();
        $email = $data['email'] ;
        $subject = "Candidature chez QuickBaluchon" ;
        $content = "Bonjour, votre candidature a été enregistrée. Nous reviendrons vers vous ultérieurement. Cordialement, l'équipe de Quick Baluchon." ;

        require_once('ApiMail.php') ;
        $mail = new ApiMail($email, $subject, $content) ;
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

        self::$_columns = ['id', 'warehouse'];
        self::$_where = ['email = ?', 'password = ?'];
        self::$_params = [$deliveryman['name'], hash('sha256', $deliveryman['password'])];

        $deliveryman = $this->get('DELIVERYMAN');
        if (count($deliveryman) == 1) {
            $id = $deliveryman[0]['id'];
            $expire = 60 * 60 * 24 * 15; // 15 days
            $role = 'deliveryman';
            $jwt = $this->generateJWT($id, $role, $expire);
            $response = [
                'id' => $id,
                'role' => $role,
                'access_token' => $jwt
            ];

            setcookie("access_token", $jwt);

            $_SESSION['id'] = $id;
            $_SESSION['role'] = $role;
            $_SESSION['jwt'] = $jwt ;
            $this->_data = $response;
        } else {
            // email/password false
            http_response_code(401);
            exit();
        }

    }

    private function updateDelivery($id, $data = null) {
        $this->checkRole(["admin", "deliveryman"], $this->_jwt);
        if ($data == null) {
            $data = $this->getJsonArray();
            $allowed = ['email', 'phone', 'volumeCar', 'radius', 'password', 'oldpassword', 'licenseIMG', 'registrationIMG'];
            if (count(array_diff(array_keys($data), $allowed)) > 0) {
                http_response_code(400);
                exit();
            }
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
        $this->checkRole(["admin"], $this->_jwt);
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
        if ($data['employed'] == 1) {
            self::$_set[] = 'employStart = ?' ;
            self::$_params[] = date("Y-m-d");
        } else {
            self::$_set[] = 'employEnd = ?' ;
            self::$_params[] = date("Y-m-d");
        }

        $this->patch('DELIVERYMAN', $id);
        $this->resetParams();

        $this->_method = 'GET' ;
        $d = $this->getDelivery($id) ;

        $email = $d['email'] ;
        $subject = "Candidature chez QuickBaluchon" ;
        if (isset($data['employed']) && $data['employed'] == 1)
            $content = "Bonjour, votre candidature a été acceptée. Bienvenue parmi les livreurs de Quick Baluchon !" ;
        else
            $content = "Bonjour, nous sommes navrés de vous annoncer que votre candidature chez Quick Baluchon a été refusée. Bonne continuation dans vos recherches." ;

        require_once('ApiMail.php') ;
        $mail = new ApiMail($email, $subject, $content) ;
    }

    public function registerFile(){
        if(!isset($_GET["id"])){
            header("Location:/deliveryman/signup");
            exit;
        }
        $id = $_GET["id"];

        $license = "uploads/license/";
        $registration = "uploads/registration/";

        if (isset($_FILES) && !empty($_FILES)) {
            $this->checkFolder($license);
            $this->checkFolder($registration);
            $_POST = [] ;

            if (isset($_GET['file']) && ($_GET['file'] == 'License' || $_GET['file'] == 'Registration')) {
                $folder = 'uploads/' . strtolower($_GET['file']) . '/';
                $file = $this->saveFileFolder($id, $_GET['file'], $folder);
                if ($file) {
                    $_POST[strtolower($_GET['file']) . 'IMG'] = $file;
                    $this->updateDelivery($id, $_POST);
                }
            } else {
                $licence = $this->saveFileFolder($id, 'License', $license);
                $registration = $this->saveFileFolder($id, 'Registration', $registration);
                $_POST['licenseIMG'] = $licence ;
                $_POST['registrationIMG'] = $registration ;
                $this->updateDelivery($id, $_POST);
            }

            header("Location:/");
        } else {
            echo 'Error with the files';
            echo '$_FILES:';
        }
    }

    private function checkFolder ($folder) {
        if (!file_exists($folder)) {
            mkdir($folder, 0777, true);
        }
    }

    private function saveFileFolder (int $id, string $fileName, string $folder) {
        $fileName = 'file' . $fileName;
        $acceptable = [
            'image/jpeg',
            'image/jpg',
            'image/png',
            'image/gif',
        ];

        if(isset($_FILES[$fileName]['type'])){
            if(!in_array( $_FILES[$fileName]['type'], $acceptable ) ){
                header("Location:/deliveryman/signup");
                exit;
            }
        }

        $name = $_FILES[$fileName]['name'];
        $array = explode('.', $name);
        $extension = end($array);
        $_FILES[$fileName]['name'] = $id . "." . $extension;
        $filepath = $folder . $_FILES[$fileName]['name'];
        move_uploaded_file($_FILES[$fileName]['tmp_name'], $filepath);
        return $_FILES[$fileName]['name'] ;
    }
}
