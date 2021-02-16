<?php

class DeliveryManager extends Model{

  public function getDeliverys($fields) {
    return $this->getCollection('deliveryman', []);

  }
}
