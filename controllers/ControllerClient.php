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
                }
                header('location:' . WEB_ROOT . 'login');
                exit();
            default :
                http_response_code(500);
                exit();
        }
    }

    private function action($url) {
        if( isset($_SESSION['id']) ){
            if (method_exists($this, $url[1])) {
                if ($_SESSION['role'] == 'client')
                    $this->_id = $_SESSION['id'];
                else
                    if (isset($url[2]) && $_SESSION['role'] == 'admin')
                        $this->_id = intVal($url[2]);
                    else {
                        $this->_view = new View('Error');
                        $this->_view->generateView(['cat' => 403]);
                        return;
                    }
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

    private function signup() {
        $this->_view = new View('SignupClient');
        $this->_view->generateView([]);
    }

    private function profile($url){
        $this->_view = new View('Back');
        $this->_clientManager = new ClientManager();

        if(isset($this->_id) && $_SESSION["role"] != "admin"){
            $client = $this->_clientManager->getClient($this->_id, ['name', 'website']);
            $delivery['id'] = $_SESSION['id'];
        }
        else{
            $client = $this->_clientManager->getClient($url[0], ['name', 'website']);
            $delivery['id'] = $url[0];
        }

        if( $client != NULL && count($client) > 1 ) {
            $profile = $this->_view->generateTemplate('client_profile', $client);
        }
        else {
            http_response_code(404);
            $profile = $this->_view->generateTemplate('Error', ['code'=>404]);
            $client['website'] = '';
        }

        $this->_view->generateView(['content' => $profile, 'name' => $client['website']]);
    }

    private function bills() {
        $price = [];
        $_SESSION['price'] = [];
        $this->_view = new View('Back');

        $this->_clientManager = new ClientManager();
        $this->_billManager = new BillManager();

        $client = $this->_clientManager->getClient($this->_id, ['name', 'website']);
        $billsList = $this->_billManager->getBills($this->_id, ['id', 'grossAmount', 'netAmount','dateBill', 'paid', 'pdfPath']);
        $buttonsValues = [
            'pay' => 'payer',
        ];

        if($billsList != null){
            $i = 0;
            foreach ($billsList as $bill) {
                foreach($buttonsValues as $link => $inner){
                    $id = $bill['id'];
                    if($bill['paid'] == 0){
                        $_SESSION["price"][] = ["price$id" => $bill['netAmount']];
                        if ($_SESSION['role'] == 'client')
                            $buttons[] = '<a href="'. WEB_ROOT . "client/$link/" . $id .'"><button type="button" class="btn btn-primary btn-sm">' . $inner . '</button></a>';
                        else
                            $buttons[] = '<span>&#x10102</span>' ;
                    }else{
                        $buttons[] = '<span>&#x2713</span>';
                    }
                    $buttons[] = '<a href="'. WEB_ROOT . $bill['pdfPath'] .'"><button type="button" class="btn btn-primary btn-sm">visualiser</button></a>';
                    unset($bill['pdfPath']) ;
              }

              if(isset($buttons))
                $rows[] = array_merge($bill, $buttons);
              else
                $rows[] = $bill;
            unset($rows[$i]["paid"]);
            $i++;
            $buttons = [];
            }
        }else {
            $rows = [];
        }

        $cols = ["#", "Montant brut","Montant net", "date", "payée", "visualiser"];
        $bills = $this->_view->generateTemplate('table', ['cols' => $cols, 'rows' => $rows]);
        $this->_view->generateView(['content' => $bills, 'name' => $client['website']]);
    }

    private function history(){
        $this->_view = new View('Back');

        $this->_packageManager = new PackageManager();
        $this->_clientManager = new ClientManager();

        $package = $this->_packageManager->getClientPackages($this->_id, ['id', 'weight', 'volume', 'address', 'email', 'delay', 'dateDelivery', 'status', 'dateDeposit']);
        $client = $this->_clientManager->getClient($this->_id, ['website']);

        $cols = ['Poids', 'Volume', 'Adresse', 'E-mail', 'Délais', 'Date de livraison', 'Déposé le'];
        $package = $this->_view->generateTemplate('table', ['cols' => $cols, 'rows' => $package]);
        $this->_view->generateView(['content' => $package, 'name' => $client['website']]);
    }

    public function pay ($url) {
        $bill = intval($url[0]);
        $this->_view = new View('Back');
        $stripe = $this->_view->generateTemplate('stripe', ["bill" => $bill]);
        $this->_view->generateView(['content' => $stripe, 'name' => "stripe"]);
    }

}
