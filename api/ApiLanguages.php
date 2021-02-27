<?php

require_once('Api.php');

class ApiLanguages extends Api {

    private $_method;
    private $_data = [];

    public function __construct($url, $method) {

        $this->_method = $method;

        switch ($method) {
            case 'PUT' : $this->_data = $this->addLanguage();break;
            case 'DELETE' : $this->_data = $this->deleteLanguage(); break;
            default: http_response_code(404); exit();
        }
    }

    private function addLanguage () {
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

    private function deleteLanguage () {
        $data = $this->getJsonArray() ;
        $lang = json_decode(file_get_contents('languages.json'), true);
        if ($this->checkPresent($lang, $data)) {
            print_r($lang) ;
            unset($lang[$data['shortcut']]) ;
            print_r($lang) ;
            $json = json_encode($lang, JSON_PRETTY_PRINT) ;
            file_put_contents('languages.json', $json);
        } else
            http_response_code(404) ;
    }

    private function checkPresent ($list, $lang) {
        if (array_key_exists($lang['shortcut'], $list)) return true ;
        if (isset($lang['language']))
            foreach ($list as $l => $data) {
                if ($data['language'] == $lang['language']) return true ;
            }
        return false ;
    }
}
