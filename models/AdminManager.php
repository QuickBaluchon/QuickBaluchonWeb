<?php

class AdminManager extends Model {

    public function login($username, $password): ?array {
        $credentials = [
            'username' => $username,
            'password' => $password
        ];
        $result = $this->curl(API_ROOT . 'admin/login', $credentials);
        return is_array($result) ? $result : null;
    }

    public function getStaffs($fields) {
      return $this->getCollection('admin/getListStaff', ["fields" => join(',',$fields)]);
    }
}
