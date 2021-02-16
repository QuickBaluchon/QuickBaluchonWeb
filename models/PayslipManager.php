<?php

class PayslipManager extends Model{

  public function getPayslip($id,$fields) {
    return $this->getCollection('payslip', ["fields" => join(',',$fields), "deliveryman" => $id]);

  }
}
