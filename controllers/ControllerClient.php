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

    private function signup() {
        $this->_view = new View('SignupClient');
        $this->_view->generateView([]);
    }

    private function profile($id){
        $this->_view = new View('Back');
        $this->_clientManager = new ClientManager();

        if(isset($this->_id))
            $client = $this->_clientManager->getClient($this->_id, ['name', 'website']);
        else
            $client = $this->_clientManager->getClient($id[0], ['name', 'website']);

        $profile = $this->_view->generateTemplate('client_profile', $client);
        $this->_view->generateView(['content' => $profile, 'name' => $client['website']]);
    }

    private function bills() {

        $this->_view = new View('Back');

        $this->_clientManager = new ClientManager();
        $this->_billManager = new BillManager();

        $client = $this->_clientManager->getClient($this->_id, ['name', 'website']);
        $billsList = $this->_billManager->getBills($this->_id, ['id', 'grossAmount', 'netAmount','dateBill', 'paid']);
        $buttonsValues = [
            'pay' => 'payer',
        ];

        if($billsList != null){
            $i = 0;
            foreach ($billsList as $bill) {
                foreach($buttonsValues as $link => $inner){
                    $id = $bill['id'];
                    if($bill['paid'] == 0){

                        $_SESSION["price$id"] = $bill['netAmount'];
                        $buttons[] = '<a href="'. WEB_ROOT . "client/$link/" . $id .'"><button type="button" class="btn btn-primary btn-sm">' . $inner . '</button></a>';
                    }else{
                        $buttons[] = '<span>déjà payé</span>';
                    }
                    $buttons[] = '<a href="'. WEB_ROOT . "client/createBillPdf/" . $id .'"><button type="button" class="btn btn-primary btn-sm">visualiser</button></a>';

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

        $cols = ["#", "Montant brut","Montant net", "date", "payer", "visualiser"];
        $bills = $this->_view->generateTemplate('table', ['cols' => $cols, 'rows' => $rows]);
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

    public function pay ($url) {
        $bill = intval($url[0]);
        $this->_view = new View('Back');
        $stripe = $this->_view->generateTemplate('stripe', ["bill" => $bill]);
        $this->_view->generateView(['content' => $stripe, 'name' => "stripe"]);
    }

    public function createBillPdf($id){
        require_once __DIR__ . "/../media/fpdf/fpdf.php";
        $this->_billManager = new BillManager();
        $this->_packageManager = new PackageManager();

        $dateBill = $this->_billManager->getBill($id[0], ['dateBill']);
        $packages = $this->_packageManager->getPackages(
            ["weight", "volume", "delay", "PRICELIST.ExpressPrice", "PRICELIST.StandardPrice"],
            ["PRICELIST","PACKAGE.pricelist","PRICELIST.id"],
            $dateBill["dateBill"],
            $this->_id);
        if ($packages != null) {
            $totalPackage = $this->calculTotal($packages);
            $cols = ["weight", 'volume', 'delay', 'Price'];
            $pdf = new FPDF();
            $pdf->AddPage();
            $pdf->SetFont('Arial', '', 12);
            foreach ($cols as $key) {
                $pdf->Cell(40, 20, "$key");
            }
            $pdf->Ln(10);
            foreach ($totalPackage as $package) {
                foreach ($package as $key => $value) {
                    $pdf->Cell(40, 20, "$value");
                }
                $pdf->Ln(10);
            }

            $pdf->Output();
        }
    }

    public function calculTotal($packages){
        $total = 0;
        $i = 0;
        foreach($packages as $package) {
            if($package["delay"] == 2){
                $total += $package["ExpressPrice"];
                unset($packages[$i]["StandardPrice"]);
            }
            else{
                $total += $package["StandardPrice"];
                unset($packages[$i]["ExpressPrice"]);
            }
            $i++;
        }
        $packages[] = ["total" => $total];
        return $packages;
    }

}
