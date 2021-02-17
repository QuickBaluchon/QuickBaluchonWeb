<?php

class DeliveryManager extends Model{

  public function getDeliverys($fields) {
    return $this->getCollection('deliveryman', ["fields" => $fields]);
  }
  public function getDelivery($id ,$fields) {
    return $this->getRessource('deliveryman', $id , ["fields" => join(',',$fields)]);
  }
}
