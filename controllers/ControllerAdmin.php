<?php

require_once('views/View.php');

class ControllerAdmin
{

  private $_clientManager;
  private $_WarehousesManager;
  private $_deliveryManager;
  private $_pricelistManager;
  private $_languagesManager ;
  private $_view;
  private $_notif;

  public function __construct($url)
  {
    if (!isset($url[1])) {
      header('location:' . WEB_ROOT . 'admin/clients');
      exit();
    }

    $_SESSION['role'] = 'admin';

    $actions = ['clients', 'pricelist', 'deliveryman', 'languages', 'warehouses', 'oneSignal', 'updatePricelist'];
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

    $buttonsValues = [
        'profile' => 'données personnelles',
        'history' => 'Historique',
        'bills' => 'Factures'
    ];

    foreach ($list as $client) {
        foreach($buttonsValues as $link => $inner){
        $buttons[] = '<a href="'. WEB_ROOT . "admin/client/$link/" . $client['id'] .'"><button type="button" class="btn btn-primary btn-sm">' . $inner . '</button></a>';
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


  private function deliverymen($url) {
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

    $buttonsValues = [
        'updateWarehouse' => 'update',
    ];

    foreach ($list as $warehouse) {
        foreach($buttonsValues as $link => $inner){
        $buttons[] = '<button onclick="'. $link .'('.$warehouse["id"].')" id="'.$warehouse["id"].'" type="button" class="btn btn-primary btn-sm">' . $inner . '</button>';
      }

      $rows[] = array_merge($warehouse, $buttons);
      $buttons = [];
    }

    $cols = ['#', 'address', 'volume', 'AvailableVolume', 'update'];
    if(!isset($rows)) $rows = [];
    $warehouse = $this->_view->generateTemplate('table', ['cols' => $cols, 'rows' => $rows]);
    $this->_view->generateView(['content' => $warehouse, 'name' => 'QuickBaluchon']);

  }

  private function pricelist($url) {
    $this->_view = new View('Back');

    $this->_pricelistManager = new PricelistManager;
    $list = $this->_pricelistManager->getPricelists([]);

    $buttonsValues = [
        'updatePricelist' => 'modifier',
    ];

    foreach ($list as $package) {
        foreach($buttonsValues as $link => $inner){
        $buttons[] = '<a href="'. WEB_ROOT . "admin/$link/" . $package['id'] .'"><button type="button" class="btn btn-primary btn-sm">' . $inner . '</button></a>';
      }

      $rows[] = array_merge($package, $buttons);
      $buttons = [];
    }

    $cols = ['#', 'Max wheight', 'Express price', 'Standard price', 'application date'];
    $pricelist = $this->_view->generateTemplate('table', ['cols' => $cols, 'rows' => $rows]);
    $this->_view->generateView(['content' => $pricelist, 'name' => 'QuickBaluchon']);
  }

  private function updatePricelist($url) {
    $this->_view = new View('UpdatePricelist');

    $this->_view->generateView([]);
  }

    private function languages($url) {
        $this->_view = new View('Back');
        $this->_languagesManager = new LanguagesManager ;

        switch($url[0] != '') {
            case 0 :
                $data = $this->allLanguages() ;
                break ;
            default:
                $data = $this->oneLanguage($url[0]) ;
                break ;
        }

        $this->_view->generateView(['content' => $data, 'name' => 'QuickBaluchon']);

    }

    private function allLanguages () {
        $list = $this->_languagesManager->getLanguages() ;

        foreach ($list as $lang) {
            $button = ['<a href="'. WEB_ROOT . 'admin/languages/' . $lang['1'] . '"><button type="button" class="btn btn-primary btn-sm">Modifier</button></a>'];
            $rows[] = array_merge($lang, $button);
        }

        $rows[] = [
            '<input type="text" class="form-control" id="language" placeholder="language">',
            '<input type="text" class="form-control" id="shortcut" placeholder="SH">',
            '<input type="text" class="form-control" id="emoji" placeholder="alt-codes.net/flags">',
            '<button type="button" class="btn btn-success btn-sm" onclick="addLanguage()">Ajouter</button>'
        ] ;

        $cols = ['Language', 'Shortcut', 'Emoji', 'Modify'] ;

        return $languages = $this->_view->generateTemplate('table', ['cols' => $cols, 'rows' => $rows]) ;
    }

    private function oneLanguage ($sh) {
        return "Hello" ;
    }

}
