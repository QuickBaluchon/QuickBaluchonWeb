<?php

class PayslipManager extends Model{

  public function getPayslip($fields) {
    return $this->getCollection('payslip', []);

  }
}
