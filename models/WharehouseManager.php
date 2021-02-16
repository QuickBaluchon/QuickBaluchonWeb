<?php

class WharehouseManager extends Model{

  public function getWharehouses($fields) {
    return $this->getCollection('wharehouse', []);

  }
}
