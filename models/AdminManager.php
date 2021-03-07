<?php

class AdminManager extends Model {
    public function getListStaff($fields){
        return $this->getCollection('admin', [ "fields" => join(',',$fields));
    }

    public function login($username, $password): ?array {
        $credentials = [
            'username' => $username,
            'password' => $password
        ];
        $result = $this->curl(API_ROOT . 'admin/login', $credentials);
        return is_array($result) ? $result : null;
    }
}
