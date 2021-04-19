<?php

class PayslipManager extends Model{

  public function getPayslip($fields, $deliveryman = NULL, $id = NULL) {
    return $this->getCollection('payslip', ["fields" => join(',',$fields), "deliveryman" => $deliveryman, "id" => $id]);
  }
}
