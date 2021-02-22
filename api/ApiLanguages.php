<?php

require_once('Api.php');

class ApiLanguages extends Api {

    private $_method;
    private $_data = [];

    public function __construct($url, $method) {

        $this->_method = $method;

        switch ($method) {
            case 'PUT' : $this->_data = $this->addLanguage();break;
            // case 'PATCH' : $this->updateDelivery($id); break;
            default: http_response_code(404); exit();
        }
    }

    private function addLanguage() {
        $data = $this->getJsonArray() ;
        $file = fopen('languages.txt', 'a+') ;
        fputs($file, $data['language'] . ':' . $data['shortcut'] . ':' . $data['flag'] . "\n") ;
        fclose($file) ;
    }
}
