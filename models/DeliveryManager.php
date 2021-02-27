<?php

class DeliveryManager extends Model{

  public function getDeliverys($fields, $warehouse) {
    if($warehouse!= NULL){
        return $this->getCollection('deliveryman', ["fields" => join(',',$fields), "warehouse" => $warehouse]);
    }else{
        return $this->getCollection('deliveryman', ["fields" => join(',',$fields)]);
    }
  }
  public function getDelivery($id ,$fields) {
    return $this->getRessource('deliveryman', $id , ["fields" => join(',',$fields)]);
  }
  public function getDeliveryNotEmployed() {
    return $this->getCollection('deliveryman', ["employed" => 0]);
  }
}
