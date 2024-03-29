<?php


require_once('Api.php');

class ApiBill extends Api
{

    private $_method;
    private $_data = [];
    private $_tva = 1.2;

    public function __construct($url, $method)
    {
        $this->_method = $method;

        if (count($url) == 0) {
            switch($method) {
                case 'GET': $this->_data = $this->getListBills();     // list of bills - /api/bill
                case 'POST': $this->addBills(); break;
                default: $this->catError(405); break;
            }

        } elseif (($id = intval($url[0])) !== 0)      // details one bills - /api/bill/{id}
            switch ($method) {
                case 'GET': $this->_data = $this->getBill($id);break;
                case 'PATCH':$this->patchBill($id);break;
                default: $this->catError(405); break;
            }
        else
            $this->catError(400);


        echo json_encode($this->_data, JSON_PRETTY_PRINT);

    }

    public function getListBills(): array {
        if($this->_method != 'GET') $this->catError(405);

        if(isset($_GET['client'])) {
            self::$_where[] = 'client = ?';
            self::$_params[] = intval($_GET['client']);
        }
        if(isset($_GET['paid'])) {
            self::$_where[] = 'paid = ?';
            self::$_params[] = intval($_GET['paid']);
        }

        $columns = ['id', 'client', 'grossAmount', 'netAmount', 'dateBill', 'pdfPath', 'paid'];
        $list = $this->get('MONTHLYBILL', $columns);
        $bills = [];
        if( $list != null ){
            foreach ($list as $bill) {
                $bills[] = $bill;
            }
        }
        return $bills;
    }

    public function getBill($id): array
    {
        //$this->authentication(['admin'], [$id]);
        self::$_where[] = 'id = ?';
        self::$_params[] = $id;
        $columns = ['id', 'client', 'grossAmount', 'netAmount', 'dateBill', 'pdfPath', 'paid'];
        $client = $this->get('MONTHLYBILL', $columns );
        if (count($client) == 1)
            return $client[0];
        else
            return [];
    }

    public function patchBill($id){
        $data = $this->getJsonArray();
        $allowed = ['paid'];

        if (count(array_diff(array_keys($data), $allowed)) > 0) {
            http_response_code(400);
            exit();
        }

        self::$_set[] = "paid = ?" ;
        self::$_params[] = $data['paid'];

        $this->patch('MONTHLYBILL', $id);
    }

    public function addBills ()
    {
        $clients = $this->getExternData('ApiClient', [], 'getListClients');
        require_once('ApiPackage.php');
        foreach ($clients as $c) {
            $_GET = [];
            $_GET['inner'] = 'PRICELIST, PRICELIST.id, PACKAGE.pricelist' ;
            $_GET['client'] = $c['id'] ;
            $date = date_create(date("Y-m"));
            date_sub($date, date_interval_create_from_date_string("1 month"));
            $_GET['date'] = date_format($date,"Y-m");

            $pkgs = $this->getExternData('ApiPackage', $_GET, 'getListPackages');

            if ($pkgs != null) {
                $total = $this->calculTotal($pkgs);
                $date = date("Y-m");

                $id = $this->insertBillDB($total, $date, $c['id']);
                $this->createBillPdf($id, $pkgs, $total) ;
            }
        }
    }

    private function insertBillDB ($total, $date, $client) {
        self::$_columns = ['grossAmount', 'netAmount', 'tva', 'dateBill', 'paid', 'client'] ;
        self::$_params = [
            $total * $this->_tva,
            $total,
            20,
            $date . '-01',
            0,
            $client
        ] ;
        return $this->add('MONTHLYBILL') ;

    }

    public function createBillPdf($id, $packages, $total){
        require_once($_SERVER['DOCUMENT_ROOT'] . "/media/fpdf/fpdf.php");
        $this->_billManager = new BillManager();

        $cols = ['Date deposit', "weight", 'volume', 'delay', 'Price'];
        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial', '', 20);
        $pdf->Cell(160, 20, "Quick Baluchon");
        $pdf->Ln(10);
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(160, 20, "Facture No : " . $id);
        $pdf->Ln(30);

        $pdf->SetFont('Arial', '', 14);
        foreach ($cols as $key) {
            $pdf->Cell(40, 20, "$key");
        }
        $pdf->Ln(10);

        $pdf->SetFont('Arial', '', 12);
        foreach ($packages as $package) {
            if ($package['delay'] == 2)
                $price = $package['ExpressPrice'];
            else
                $price = $package['StandardPrice'];

            $pdf->Cell(40, 20, $package['dateDeposit']);
            $pdf->Cell(40, 20, $package['weight']);
            $pdf->Cell(40, 20, $package['volume']);
            $pdf->Cell(40, 20, $package['delay']);
            $pdf->Cell(40, 20, $price);

            $pdf->Ln(10);
        }

        $pdf->Ln(20);
        $pdf->Cell(160, 20, "Total");
        $pdf->Cell(40, 20, $total . " EUR");
        $pdf->Ln(10);

        $pdf->Cell(160, 20, "TVA");
        $pdf->Cell(40, 20, ($this->_tva - 1) * 100 . " %");
        $pdf->Ln(10);

        $pdf->Cell(160, 20, "Total TTC");
        $pdf->Cell(40, 20, $total * $this->_tva . " EUR");
        $pdf->Ln(10);
        $filename = $_SERVER['DOCUMENT_ROOT'] . "/uploads/bills/$id.pdf" ;
        $pdf->Output($filename, 'F');
    }

    public function calculTotal($packages){
        $total = 0;
        foreach($packages as $package) {
            if ($package["delay"] == 2) {
                $total += $package["ExpressPrice"];
                $package['price'] = $package["ExpressPrice"];
            } else {
                $total += $package["StandardPrice"];
                $package['price'] = $package['StandardPrice'];
            }
        }
        return $total;
    }
}
