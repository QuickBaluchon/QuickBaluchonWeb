<?php
require_once('views/View.php');

class ControllerClient
{

    private $_clientManager;
    private $_billManager;
    private $_packageManager;
    private $_view;
    private $_id;

    public function __construct($url) {

        switch ( (int)isset($url[1]) ) {
            case 1 :        //      /client/action
                $this->action($url);
                break;
            case 0 :        //      /client
                if( isset($_SESSION['id']) ){
                    header('location:' . WEB_ROOT . 'client/profile');
                    exit();
                }else {
                    header('location:' . WEB_ROOT . 'login');
                    exit();
                }
                break;
            default :
                http_response_code(500);
                exit();
        }

    }

    private function action($url) {
        if( isset($_SESSION['id']) ){
            if (method_exists($this, $url[1])) {
                $this->_id = $_SESSION['id'];
                $method = $url[1];
                $this->$method(array_slice($url, 2));
            } else {
                http_response_code(404);
                exit();
            }
        } else {
            if( strtolower($url[1]) === 'signup' )
                $this->signup();
            else {
                if( method_exists($this, $url[1]) ) {
                    header('location:' . WEB_ROOT . 'login');
                    exit();
                } else {
                    http_response_code(404);
                    exit();
                }

            }
        }
    }

    private function signup()
    {
        $this->_view = new View('SignupClient');
        $this->_view->generateView([]);
    }

    private function profile()
    {
        $this->_view = new View('Back');

        $this->_clientManager = new ClientManager();
        $client = $this->_clientManager->getClient($this->_id, ['name', 'website']);

        $profile = $this->_view->generateTemplate('client_profile', $client);
        $this->_view->generateView(['content' => $profile, 'name' => $client['website']]);
    }

    private function bills()
    {
        $this->_view = new View('Back');

        $this->_clientManager = new ClientManager();
        $this->_billManager = new BillManager();

        $client = $this->_clientManager->getClient($this->_id, ['name', 'website']);
        $billsList = $this->_billManager->getBills($this->_id, ['dateBill', 'id', 'grossAmount', 'paid']);

        $cols = ['Mois', 'Nb colis', 'Prix', 'Télécharger', 'Statut'];
        $bills = $this->_view->generateTemplate('table', ['cols' => $cols, 'rows' => $billsList]);
        $this->_view->generateView(['content' => $bills, 'name' => $client['website']]);
    }

    private function history(){
        $this->_view = new View('Back');

        $this->_packageManager = new PackageManager();
        $this->_clientManager = new ClientManager();

        $package = $this->_packageManager->getClientPackages($this->_id, ['id', 'weight', 'volume', 'address', 'email', 'delay', 'dateDelivery', 'status', 'dateDeposit']);
        $client = $this->_clientManager->getClient($this->_id, ['website']);

        $cols = ['#', 'Poids', 'Volume', 'Adresse', 'E-mail', 'Délais', 'Date de livraison', 'Status', 'Déposé le'];
        $package = $this->_view->generateTemplate('table', ['cols' => $cols, 'rows' => $package]);
        $this->_view->generateView(['content' => $package, 'name' => $client['website']]);
    }

}
