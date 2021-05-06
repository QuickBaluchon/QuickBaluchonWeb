<?php

class PricelistManager extends Model{

    public function getPricelists($fields) {
        return $this->getCollection('pricelist', ["fields" => join(',',$fields)]);
    }

    public function getPricelist($id, $fields) {
        return $this->getRessource('pricelist', $id , ["fields" => join(',',$fields)]);
    }
}
