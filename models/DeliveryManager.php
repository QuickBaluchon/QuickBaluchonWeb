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
  public function getDeliveryNotEmployed($fields) {
    return $this->getCollection('deliveryman', ["fields" => join(',',$fields), "employed" => 0]);
  }
  public function getNbTotalColis($id, $month, $year) {
      $stats = [
          'stats' => "package",
          'month' => $month,
          'year' => $year,
          'deliveryman' => $id
      ];

      return $result = $this->curl(API_ROOT . 'deliverymanstats/1', $stats);

  }

  public function getHeavyPackage($id, $month, $year) {
      $stats = [
          'stats' => "heavy",
          'month' => $month,
          'year' => $year,
          'deliveryman' => $id
      ];

      return $result = $this->curl(API_ROOT . 'deliverymanstats/1', $stats);
  }

  public function getNbTotalColisDelivered($id, $month, $year) {
      $stats = [
          'stats' => "package",
          'month' => $month,
          'year' => $year,
          'delivery' => 'true',
          'deliveryman' => $id
      ];

      return $result = $this->curl(API_ROOT . 'deliverymanstats/1', $stats);

  }

}
