<?php

require_once('Api.php');

class ApiPayslip extends Api {

  private $_method;
  private $_data = [];
  private $id = 5;
  private $_apiPayslip;
  public function __construct($url, $method) {


    $this->_method = $method;


    if (count($url) == 0){
        switch ($method) {
            case 'GET': $this->_data = $this->getListPayslip();break;
            case 'POST': $this->addPayslip();break;
            default: $this->catError(405); break ;
        }
    }


    elseif ( ($id = intval($url[0])) !== 0 ){     // details one packages - /api/package/{id}
        switch ($method) {
            case 'POST': $this->_data = $this->getPayslip($id);break;
            case 'GET': $this->createPdfBill($id);break;
            default: $this->catError(405);break ;
        }

    }
    echo json_encode( $this->_data, JSON_PRETTY_PRINT );

  }

  public function getListPayslip (): array  {
    $packages = [];
    if($this->_method != 'GET') $this->catError(405);

    if(isset($_GET['deliveryman']) && $_GET['deliveryman'] != NULL) {
      self::$_where[] = 'deliveryman = ?';
      self::$_params[] = intval($_GET['deliveryman']);
    }
    if(isset($_GET['id']) && $_GET['id'] != NULL) {
      self::$_where[] = 'id = ?';
      self::$_params[] = intval($_GET['id']);
    }

    self::$_columns = ["id", "grossAmount", "bonus", "datePay", "pdfPath",	"paid"];
    self::$_offset = isset($_GET['offset']) ? intval($_GET['offset']) : 0;
    self::$_limit = isset($_GET['limit']) ? intval($_GET['limit']) : 20;

    $Payslip = $this->get('PAYSLIP', self::$_columns);

    return $Payslip;
  }

  public function getPayslip($id): array {
    //if($this->_method != 'GET') $this->catError(405);

    //$this->authentication(['admin'], [$id]);
    $columns = ["grossAmount", "bonus", "datePay" ];
    self::$_where[] = 'id = ?';
    self::$_params[] = $id;
    $Payslip = $this->get('PAYSLIP', $columns);
    if( count($Payslip) == 1 )
      return $Payslip[0];
    else
      return [];
  }

  public function addPayslip(){
      $data = $this->getJsonArray();
      $allowed = ["grossAmount",'bonus', 'datePay', 'paid', 'deliveryman'];

      if( count(array_diff(array_keys($data), $allowed)) > 0 ) {
          http_response_code(400);
          exit(0);
      }

      self::$_columns = ["grossAmount",'bonus', 'datePay', 'paid', 'deliveryman'];
      self::$_params = [$data["grossAmount"], $data["bonus"], $data["datePay"], $data["paid"], $data["deliveryman"]];

      $this->add('PAYSLIP');
    }

    public function createPdfBill($id){
        require_once($_SERVER['DOCUMENT_ROOT'] . "/media/fpdf/fpdf.php");
        $billValue = $this->getPayslip($id);
        $cols = ['Montant', "bonus", 'date'];
        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial', '', 20);
        $pdf->Cell(160, 20, "Quick Baluchon");
        $pdf->Ln(10);
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(160, 20, "Fiche de paie No : " . $id);
        $pdf->Ln(30);

        $pdf->SetFont('Arial', '', 14);
        foreach ($cols as $key) {
            $pdf->Cell(40, 20, "$key");
        }
        $pdf->Ln(10);
        $pdf->Cell(40, 20, $billValue["grossAmount"]);
        if(intval($billValue["bonus"]) == 10)
            $pdf->Cell(40, 20, $billValue["bonus"] . "" . "%");
        else if(intval($billValue["bonus"]) == 15)
            $pdf->Cell(40, 20, "-" . $billValue["bonus"] . "" . "%");
        else
            $pdf->Cell(40, 20, $billValue["bonus"] . " EUR");

        $pdf->Cell(40, 20, $billValue["datePay"]);
        $pdf->Ln(10);

        $filename = $_SERVER['DOCUMENT_ROOT'] . "/payslip/$id.pdf" ;
        $pdf->Output($filename, 'F');
        $this->updatePathPayslip($id);
    }

    private function updatePathPayslip($id){
        self::$_set[] = 'pdfPath = ?' ;
        self::$_params[] =  "payslip/$id.pdf" ;
        $this->patch('PAYSLIP', $id) ;
    }

  }
