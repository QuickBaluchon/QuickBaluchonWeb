<?php

abstract class Model {

  // $cond
  // [
  //  "name" => ", "nath",
  //  "offset" => 2
  //]

  protected function getCollection($collection, $cond) {

    $url = API_ROOT . $collection;
    $params = $this->strCond($cond);
    $url = $params === '' ? $url : $url . '&' . $params;

    return $this->curl($url);
  }

  protected function getRessource($collection, $id, $cond) {
    return $this->getCollection($collection. '/' . $id, $cond);
  }

  private function curl($url) {

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HEADER, 0);
    $list = json_decode(curl_exec($curl), true);
    curl_close($curl);
    return $list;

  }


  private function strCond($cond) {
    $params = [];
    foreach ($cond as $k => $v)
      $params[] = $k . '=' . $v;
    return join('&', $params);
  }


}
