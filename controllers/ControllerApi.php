<?php

class ControllerApi {

    private $_apiManager;
    private $_method;

    public function __construct($url) {
        if( !isset($url) )
            // API NOT FOUND : 404
            http_response_code(404) ;
        else{
            $url = array_slice($url,1); // remove /api/
            if( strlen($url[0]) == 0 ){

                // DUCUMENTATION API
                echo 'DOCUMENTATION';

            }elseif (count($url) > 0) {
                if( $this->getRequestMethod(["POST", "GET", "DELETE", "PUT", "PATCH"]) )
                    $this->callApi($url, $this->_method);
            }
        }
    }

    private function getRequestMethod($allowedMethods): bool {
        $method = $_SERVER["REQUEST_METHOD"];

        if( in_array($method, $allowedMethods) ) {
            $this->_method = $method;
            return true;
        }
        else{
            http_response_code(405) ; // CODE 405 : method not allowed
            echo '<img src="https://http.cat/405.jpg" alt="405">';
            return false;
        }

    }

    private function callApi($url, $method) {
        $apiClass = 'Api' . ucfirst(strtolower($url[0]));
        $apiFile = 'api/' . $apiClass . '.php' ;

        if( file_exists($apiFile) ) {
            header('Content-Type: application/json');
            require_once($apiFile);
            $url = array_slice($url,1);
            $this->_apiManager = new $apiClass($url, $method);


        } else {
            http_response_code(404) ; // API NOT FOUND : 404
            echo '<img src="https://http.cat/404.jpg" alt="404">';
        }

    }

}
