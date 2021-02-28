<?php

class DeliveryStatsManager extends Model{

    public function getPayAverage($fields, $limit, $) {
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
