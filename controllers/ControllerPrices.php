<?php

require_once('views/View.php');

class ControllerPrices {
    private $_pricelistManager;

    public function __construct($url) {
        if( isset($url) && ( !is_string($url) && count($url) > 1 )   ){
            http_response_code(404);
            throw new Exception("Page not found");
        }
        else{
            $this->_view = new View('Prices');
            $this->_pricelistManager = new PricelistManager;
            $list = $this->_pricelistManager->getPricelists([]);

            $count = count($list) ;
            for ($i = 0 ; $i < $count ; ++$i) {
                if ($list[$i]['status'] == 0) unset($list[$i]);
                else {
                    unset($list[$i]['id']);
                    unset($list[$i]['status']);
                }
            }

            $cols = ['Max weight', 'Express price', 'Standard price', 'application date'];
            $pricelist = $this->_view->generateTemplate('table', ['cols' => $cols, 'rows' => $list]);
            $this->_view->generateView(["content" => $pricelist]);
        }
    }
}
