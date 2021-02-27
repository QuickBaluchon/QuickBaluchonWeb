<?php

class BillManager extends Model{

  public function getBills($client, $fields) {
    return $this->getCollection('bill', [ "fields" => join(',',$fields),
                                                  "client" => $client]);
  }

  public function getBill($id, $fields) {
    return $this->getRessource('bill', $id , ["fields" => join(',',$fields)]);
  }

  public function getNotPaidBills($client) {
    return $this->getCollection('bill', [ "paid" => 0,"client" => $client]);
  }
}
