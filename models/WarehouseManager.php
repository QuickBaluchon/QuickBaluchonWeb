<?php

class WarehouseManager extends Model{

  public function getWarehouses($fields) {
    return $this->getCollection('warehouse', ["fields" => join(',',$fields)]);
  }

}
