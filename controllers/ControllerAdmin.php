<?php

require_once('views/View.php');

class ControllerAdmin
{

  private $_clientManager;
  private $_view;

  public function __construct($url)
  {
    if (!isset($url[1])) {
      header('location:' . WEB_ROOT . 'admin/clients');
      exit();
    }

    $actions = ['clients', 'pricelist', 'deliverymen', 'languages', 'wharehouses'];
    if (in_array($url[1], $actions)) {
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

    $buttonsValues = ['profile' => 'données personnelles',
                      'history' => 'Historique',
                      'bills' => 'Factures'];

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
}