<?php

require_once('views/View.php');

class ControllerDownload {

    private $_view;

    public function __construct($url){
        if( !is_array($url) || count($url) > 1 ){
            http_response_code(404);
            throw new Exception("Page not found");
        }
        else{
            $this->_view = new View('Download');
            $this->_view->generateView([]);
        }
    }

}
