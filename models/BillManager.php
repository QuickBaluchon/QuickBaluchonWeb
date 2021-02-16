<?php

class BillManager extends Model{

  public function getBills($client, $fields) {
    return $this->getCollection('bill', [ "fields" => join(',',$fields),
                                                  "client" => $client]);
  }

  public function getBill($id, $fields) {
    return $this->getRessource('client', $id , ["fields" => join(',',$fields)]);
  }
}