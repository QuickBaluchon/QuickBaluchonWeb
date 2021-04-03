<?php
//$this->_header = ROOT . '/templates/Front/header.php';
$this->_js = ['main', 'package'];
extract($this->_content) ;
?>

<section id="heroBanner-package">
    <div class="container col-8 d-flex flex-column justify-content-center" id="jumbo">
       <div id="webgl"></div>
    </div>
</section>
<script src="<?= WEB_ROOT.'media/script/packageWebGL.js' ?>" type="module" ></script>
