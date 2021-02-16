<?php

class ClientManager extends Model{

  public function getClients($fields) {
    return $this->getCollection('client', ["fields" => join(',',$fields)]);
  }

  public function getClient($id, $fields) {
    return $this->getRessource('client', $id , ["fields" => join(',',$fields)]);
  }
}
