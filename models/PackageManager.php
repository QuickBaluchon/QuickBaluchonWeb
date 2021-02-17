<?php

class PackageManager extends Model{
    public function getPackages($fields) {
      return $this->getCollection('package', ["fields" => $fields]);
    }
    public function getPackage($id ,$fields) {
      return $this->getRessource('package', $id , ["fields" => join(',',$fields)]);
    }

    public function getClientPackages($id, $fields) {
      return $this->getCollection('package', ["fields" => join(',',$fields), "client" => $id]);

    }
}
