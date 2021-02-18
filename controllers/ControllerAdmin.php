<?php

require_once('views/View.php');

class ControllerAdmin
{

  private $_clientManager;
  private $_WarehousesManager;
  private $_deliveryManager;
  private $_pricelistManager;
  private $_view;
  private $_notif;

  public function __construct($url)
  {
    if (!isset($url[1])) {
      header('location:' . WEB_ROOT . 'admin/clients');
      exit();
    }

    $actions = ['clients', 'pricelist', 'deliveryman', 'languages', 'wharehouses', 'oneSignal', 'updatePricelist'];
    if ( method_exists( $this ,$url[1]) ) {
      $method = $url[1];
      $this->$method(array_slice($url, 2));

    } else {
      http_response_code(404);
    }
  }

  private function clients($url)
  {
    $this->_view = new View('Back');

    $this->_clientManager = new ClientManager;
    $list = $this->_clientManager->getClients(['id', 'name']);

    $buttonsValues = ['données personnelles',
                      'Historique',
                      'Factures'];

    foreach ($list as $client) {
      for($i = 0; $i < 3; $i++){
        $buttons[] = '<a href="'. WEB_ROOT . 'admin/clients/' . $client['id'] .'"><button type="button" class="btn btn-primary btn-sm">' . $buttonsValues[$i] . '</button></a>';
      }

      $rows[] = array_merge($client, $buttons);
      $buttons = [];
    }

    $cols = ['#', 'Nom', 'Données Personnelles', 'Historique', 'Facture'];
    $clients = $this->_view->generateTemplate('table', ['cols' => $cols, 'rows' => $rows]);
    $this->_view->generateView(['content' => $clients, 'name' => 'QuickBaluchon']);
  }

  private function oneSignal($url) {
    $this->_view = new View('OneSignal');
    $this->_view->generateView([]);
  }


  private function deliveryman($url) {
    $this->_view = new View('Back');

    $this->_DeliveryManager = new DeliveryManager;
    $list = $this->_DeliveryManager->getDeliverys([]);


    $cols = ['#', 'firstname', 'lastname', 'phone', 'email', 'volumeCar', 'radius', 'IBAN','employed', 'warehouse'];
    $deliveryman = $this->_view->generateTemplate('table', ['cols' => $cols, 'rows' => $list]);
    $this->_view->generateView(['content' => $deliveryman, 'name' => 'QuickBaluchon']);
  }

  private function warehouses($url) {
    $this->_view = new View('Back');

    $this->_WarehousesManager = new WarehouseManager;
    $list = $this->_WarehousesManager->getWarehouses([]);


    $cols = ['#', 'address', 'volume', 'AvailableVolume','active'];
    $warehouse = $this->_view->generateTemplate('table', ['cols' => $cols, 'rows' => $list]);
    $this->_view->generateView(['content' => $warehouse, 'name' => 'QuickBaluchon']);
  }

  private function pricelist($url) {
    $this->_view = new View('Back');

    $this->_pricelistManager = new PricelistManager;
    $list = $this->_pricelistManager->getPricelists([]);


    $cols = ['#', 'Max wheight', 'Express price', 'Standard price', 'application date'];
    $pricelist = $this->_view->generateTemplate('table', ['cols' => $cols, 'rows' => $list]);
    $this->_view->generateView(['content' => $pricelist, 'name' => 'QuickBaluchon']);
  }

  private function updatePricelist($url) {
    $this->_view = new View('UpdatePricelist');


    $this->_view->generateView([]);
  }

}
