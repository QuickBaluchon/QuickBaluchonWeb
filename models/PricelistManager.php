<?php

class PricelistManager extends Model{

    public function getPricelists($fields, $conditions = null) {
        $data = ["fields" => join(',',$fields)];
        if ($conditions != null) $data["where"] = join(',', $conditions);
        return $this->getCollection('pricelist', $data);
    }

    public function getPricelist($id, $fields) {
        return $this->getRessource('pricelist', $id , ["fields" => join(',',$fields)]);
    }
}
