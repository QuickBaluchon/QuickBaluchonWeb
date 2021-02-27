<?php

class WarehouseManager extends Model{

  public function getWarehouses($fields) {
    return $this->getCollection('warehouse', ["fields" => join(',',$fields)]);
  }
  public function getWarehouse($id, $fields) {
    return $this->getRessource('warehouse', $id , ["fields" => join(',',$fields)]);
  }
}
