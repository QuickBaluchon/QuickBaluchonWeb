<?php

$this->_header = ROOT . '/templates/Front/header.php';
$this->_js = ['main','pricelist/update'];
$url = explode("/", $_GET["url"]);
$id = intval($url[2]);
?>

<!-- HERO BANNER LOGIN -->
<section id="heroBanner-signup">
  <input type="text" id="ExpressPrice" placeholder="Express price">
  <input type="text" id="StandardPrice" placeholder="Standars price">
  <button type="button" name="button" onclick="update(<?php echo $id ?>)">update</button>
</section>
