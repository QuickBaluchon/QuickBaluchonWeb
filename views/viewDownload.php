<?php
$this->_header = ROOT . '/templates/Front/header.php';
$this->_css = ['header', 'home'];
$this->_js = ['main', 'followPackage'] ;

extract($this->_content) ;
?>

<!-- HERO BANNER HOME -->
<section id="heroBanner-home">
    <div class="container-xl">
        <div class="row">
            <div class="col-lg">
                <div class="jumbotron bg-white">
                    <h1 class="display-4"><?= $TitleDelivery ?></h1>
                    <p class="lead"><?= $TextDelivery ?></p>
                    <p class="lead">
                        <a class="btn btn-round btn-success btn-lg" href="<?= WEB_ROOT . 'media/app/hedwige.dmg' ?>" role="button"><?= $ButtonDownload ?></a>
                    </p>
                </div>
            </div>
            <div class="col-lg">
                <img class="d-lg-block mx-auto" id="heroBanner-img-macbook" src="<?=WEB_ROOT?>media/assets/macbook.png" alt="macbook">
            </div>
        </div>
    </div>
</section>
