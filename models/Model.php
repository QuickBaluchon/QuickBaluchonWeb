<?php

abstract class Model {

    protected function getCollection($collection, $cond) {

        $url = API_ROOT . $collection;
        $params = $this->strCond($cond);
        $url = $params === '' ? $url : $url . '&' . $params;
        //$_COOKIE["access_token"]
        if(isset($_SESSION['jwt']))
            return $this->curl($url, null, ["Authorization:Bearer " . $_SESSION['jwt']]);
        else
            return $this->curl($url);

    }

    protected function getRessource($collection, $id, $cond) {
        return $this->getCollection($collection. '/' . $id, $cond);
    }

    protected function curl(string $url, array $data = null, array $headers = null): ?array {
        $curl = curl_init($url);
        if( $data != null ) {
            $payload = json_encode($data);
            curl_setopt( $curl, CURLOPT_POSTFIELDS, $payload );
            $headers[] = 'Content-Type:application/json';
            //curl_setopt( $curl, CURLOPT_HTTPHEADER, ['Content-Type:application/json']);
        }
        if($headers != null){
            curl_setopt( $curl, CURLOPT_HTTPHEADER, $headers);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        $result = json_decode(curl_exec($curl), true);
        curl_close($curl);
        return $result;
    }

    private function strCond($cond) {
        $params = [];
        foreach ($cond as $k => $v)
            $params[] = $k . '=' . $v;
        return join('&', $params);
    }


}
