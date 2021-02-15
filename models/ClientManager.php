<?php

class ClientManager extends Model{

  public function getClients() {
    return $this->getAll('CLIENT', 'Client');
  }
}
