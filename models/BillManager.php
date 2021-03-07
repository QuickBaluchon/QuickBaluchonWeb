<?php

class BillManager extends Model{

  public function getBills($client, $fields) {
    return $this->getCollection('bill', [ "fields" => join(',',$fields),
                                                  "client" => $client]);
  }

  public function getBill($id, $fields) {
    return $this->getRessource('bill', $id , ["fields" => join(',',$fields)]);
  }

  public function getNotPaidBills($id, $fields) {
    return $this->getCollection('bill', ["fields" => join(',',$fields), "client" => $id]);
  }
}
