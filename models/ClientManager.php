<?php

class ClientManager extends Model{

  public function getClients($fields) {
    return $this->getCollection('client', ["fields" => join(',',$fields)]);

  }
}
