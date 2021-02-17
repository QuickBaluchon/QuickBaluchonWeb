<?php
require_once('views/View.php');

Class ControllerClient {

  private $_clientManager;
  private $_billManager;
  private $_packageManager;
  private $_view;
  private $_id = 1;

  public function __construct($url) {
    $_SESSION['id'] = 1;
    if( !isset($_SESSION['id']) ){
      header('location:'.WEB_ROOT);
      exit();
    }
    $this->_id = $_SESSION['id'];

    if( !isset($url[1]) ){
      header('location:'.WEB_ROOT.'client/profile');
      exit();
    }

    //$this->signout();
    if ( method_exists( $this ,$url[1]) ) {
      $method = $url[1];
      $this->$method(array_slice($url, 2));
    } else {
      http_response_code(404);
    }

  }

  private function signout() {
    unset($_SESSION['id']);
    header('location:'.WEB_ROOT);
  }

  private function signup () {
    $this->_view = new View('SignupClient');
    $this->_view->generateView([]);
  }

  private function profile() {
    $this->_view = new View('Back');

    $this->_clientManager = new ClientManager();
    $client = $this->_clientManager->getClient($this->_id, ['name', 'website']);

    $profile = $this->_view->generateTemplate('client_profile', $client);
    $this->_view->generateView(['content' => $profile, 'name' => $client['website']  ]);
  }

  private function bills() {
    $this->_view = new View('Back');

    $this->_clientManager = new ClientManager();
    $this->_billManager = new BillManager();

    $client = $this->_clientManager->getClient($this->_id, ['name', 'website']);
    $billsList = $this->_billManager->getBills( $this->_id ,['dateBill', 'id', 'grossAmount', 'paid']);

    $cols = ['Mois', 'Nb colis', 'Prix', 'Télécharger', 'Statut'];
    $rows = [['janv 2021', '1', '30 €', 'O', 'Payé']];
    $bills = $this->_view->generateTemplate('table', ['cols' => $cols, 'rows' => $billsList]);
    $this->_view->generateView(['content' => $bills, 'name' => $client['website'] ]);
  }

  private function history() {
    $this->_view = new View('Back');

    $this->_packageManager = new HistoryManager();
    $this->_clientManager = new ClientManager();

    $package = $this->_packageManager->getPackages($this->_id, []);
    $client = $this->_clientManager->getClient($this->_id, ['website']);

    $cols = ['id', 'client', 'ordernb', 'weight', 'volume', 'address',	'email', 'delay', 'dateDelivery', 'status', 'excelPath', 'dateDeposit'];

    $package = $this->_view->generateTemplate('table', ['cols' => $cols, 'rows' => $package]);
    $this->_view->generateView(['content' => $package, 'name' => $client['website']  ]);
  }

}
