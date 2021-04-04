<?php
//$this->_header = ROOT . '/templates/Front/header.php';
$this->_js = ['main', 'package'];
extract($this->_content) ;
?>

<div id="webgl"></div>

<script src="<?= WEB_ROOT.'media/script/packageWebGL.js' ?>" type="module" ></script>
