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
            $list = $this->_pricelistManager->getPricelists(['maxWeight', 'ExpressPrice', 'StandardPrice'], ['status=1']);

            $count = count($list) ;
            for ($i = 0 ; $i < $count ; ++$i) {
                $j = $i - 1 ;
                $prevWeight = $list[$i]['maxWeight'];
                while ($j >= 0 && $prevWeight == $list[$i]['maxWeight']) {
                    if (isset($list[$j])) {
                        $prevWeight = $list[$j]['maxWeight'] ;
                        if ($prevWeight == $list[$i]['maxWeight']) {
                            unset($list[$i]);
                            break;
                        }
                    }
                    $j--;
                }
            }

            $cols = ['Max weight', 'Express price', 'Standard price'];
            $pricelist = $this->_view->generateTemplate('table', ['cols' => $cols, 'rows' => $list]);
            $this->_view->generateView(["content" => $pricelist]);
        }
    }
}
