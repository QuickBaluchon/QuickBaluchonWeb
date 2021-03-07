<?php
$this->_header = ROOT . '/templates/Front/header.php';
$this->_js = ['main', 'package'];
$this->_header = ROOT . '/templates/Front/header.php';
extract($this->_content) ;
?>

<section id="heroBanner-login">
    <div class="container col-8 d-flex flex-column justify-content-center">
        <div class="row">
            <div class="col">

                <div class="jumbotron bg-white" id="jumbotron">
                    <h1 class="display-4"><?= $Title ?> <?= $data['id'] ?></h1>
                    <p>Un jour, il y aura du WebGL ici :)</p>
                </div>
            </div>
        </div>
    </div>
</section>
