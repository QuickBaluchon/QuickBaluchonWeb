<?php

class HistoryManager extends Model{

  public function getPackages($id, $fields) {
    return $this->getCollection('package', ["fields" => join(',',$fields), "client" => $id]);

  }


}
