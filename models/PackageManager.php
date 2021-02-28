<?php

class PackageManager extends Model{
    public function getPackages($fields, $inner = NULL, $date = NULL, $client = NULL) {
        if($inner != NULL)
            return $this->getCollection('package', ["fields" => join(',',$fields), "inner" => join(',',$inner), "date" => $date, "client" => $client]);
        else
            return $this->getCollection('package', ["fields" => join(',',$fields)]);
    }

    public function getPackage($id ,$fields, $inner = NULL) {
        if($inner != NULL)
            return $this->getRessource('package', $id , ["fields" => join(',',$fields), "inner" => join(',',$inner)]);
        else
            return $this->getRessource('package', $id , ["fields" => join(',',$fields)]);
    }

    public function getClientPackages($id, $fields) {
      return $this->getCollection('package', ["fields" => join(',',$fields), "client" => $id]);
    }
}
