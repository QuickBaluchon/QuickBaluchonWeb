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
        $lang = json_decode(file_get_contents('languages.json'), true);
        if ($this->checkPresent($lang, $data)) {
            http_response_code(409) ;
            return ;
        }
        $lang[$data['shortcut']] = [
            "language" => $data['language'],
            "flag" => $data['flag']
        ];
        $json = json_encode($lang, JSON_PRETTY_PRINT);
        file_put_contents('languages.json', $json);
    }

    private function checkPresent($list, $lang) {
        foreach ($list as $l => $data) {
            if ($l == $lang['shortcut'] || $data['language'] == $lang['language']) return true ;
        }
        return false ;
    }
}
