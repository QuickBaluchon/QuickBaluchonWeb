<?php

require_once('Api.php');

class ApiLanguages extends Api {

    private $_method;
    private $_data = [];

    public function __construct($url, $method) {

        $this->_method = $method;

        switch ($method) {
            case 'GET' : $this->_data = $this->switchLanguage($url) ; break ;
            case 'PUT' : $this->_data = $this->addLanguage();break;
            case 'DELETE' : $this->_data = $this->deleteLanguage(); break;
            default: http_response_code(404); exit();
        }
    }

    private function switchLanguage ($url) {
        if (empty($url[0]) || strlen($url[0]) != 2) {
            http_response_code(401);
            return ;
        }
        if (!key_exists($url[0], $_SESSION['langs'])) {
            http_response_code(409) ;
            return ;
        }

        $_SESSION['defaultLang'] = $_SESSION['langs'][$url[0]] ;
    }

    private function addLanguage () {
        $data = $this->getJsonArray() ;
        $lang = json_decode(file_get_contents('languages.json'), true);
        if ($this->checkPresent($lang, $data)) {
            http_response_code(409) ;
            return ;
        }
        $lang[$data['shortcut']] = [
            "shortcut" => $data["shortcut"],
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
