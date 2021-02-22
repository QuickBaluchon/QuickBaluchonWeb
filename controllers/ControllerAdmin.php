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

        if (count($url) == 0 || $url[0] == '') {
            $data = $this->allLanguages() ;
        } else {
            $data = $this->oneLanguage($url[0])  ;
        }

        $this->_view->generateView(['content' => $data, 'name' => 'QuickBaluchon']);

    }

    private function allLanguages () {
        $list = $this->_languagesManager->getLanguages() ;

        foreach ($list as $lang) {
            $button = ['<button type="button" class="btn btn-danger btn-sm" onclick="dropLanguage(' . $lang['1'] . ')">Supprimer</button>'];
            $rows[] = array_merge($lang, $button);
        }

        $rows[] = [
            '<input type="text" class="form-control" id="language" placeholder="language">',
            '<input type="text" class="form-control" id="shortcut" placeholder="SH">',
            '<input type="text" class="form-control" id="flag" placeholder="alt-codes.net/flags">',
            '<button type="button" class="btn btn-success btn-sm" onclick="addLanguage()">Ajouter</button>'
        ] ;

        $cols = ['Language', 'Shortcut', 'Flag', 'Modify'] ;

        return $this->_view->generateTemplate('table', ['cols' => $cols, 'rows' => $rows]) ;
    }

    private function oneLanguage ($sh) {
        $language = $this->_languagesManager->getLanguage($sh) ;
        if (empty($language))
            return '<img src="https://http.cat/404.jpg" alt="404">' ;

        $rows[] = [
            '<input type="text" class="form-control" id="language" placeholder="language" value="' . $language[0] . '">',
            '<input type="text" class="form-control" id="shortcut" placeholder="SH"  value="' . $language[1] . '">',
            '<input type="text" class="form-control" id="flag" placeholder="alt-codes.net/flags"  value="' . $language[2] . '">',
            '<button type="button" class="btn btn-primary btn-sm" onclick="updateLanguage()">Modifier</button>'
        ] ;

        $cols = ['Language', 'Shortcut', 'Flag', 'Modify'] ;

        return $this->_view->generateTemplate('table', ['cols' => $cols, 'rows' => $rows]) ;
    }

}
