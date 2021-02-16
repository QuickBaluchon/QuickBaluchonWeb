<?php

abstract class Model {

  // $cond
  // [
  //  "name" => ", "nath",
  //  "offset" => 2
  //]

  protected function getCollection($collection, $cond) {

    $url = API_ROOT . $collection;
    $params = [];
    foreach ($cond as $k => $v)
      $params[] = $k . '=' . $v;
    $params = join('&', $params);
    $url = strlen($params) === 0 ? $url : $url . '&' . $params;

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HEADER, 0);
    $list = json_decode(curl_exec($curl), true);
    curl_close($curl);
    return $list;
  }

  protected function getRessource($collection, $id, $cond) {

  }
}
