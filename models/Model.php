<?php

abstract class Model {
  private static $_Db;

  private function setDb() {
    self::$_Db = $pdo = new PDO('mysql:host=localhost;dbname=hedwige;port=8889', 'root', 'root');
    self::$_Db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
  }

  protected function getDb() {
    if( self::$_Db == null )
      self::setDb();
    return self::$_Db;
  }

  protected function getAll($table, $obj) {
    $var = [];
    $req = $this->getDb()->prepare('SELECT * FROM ' . $table . ' ORDER BY id desc');
    $req->execute();
    while($data = $req->fetch(PDO::FETCH_ASSOC))
      $var[] = new $obj($data);
    $req->closeCursor();
    return $var;
  }
}
