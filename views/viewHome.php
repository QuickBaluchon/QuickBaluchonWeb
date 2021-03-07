<?php
$this->_header = ROOT . '/templates/Front/header.php';
$this->_css = ['header', 'home'];
$this->_js = ['main'] ;

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
            <a class="btn btn-round btn-primary btn-lg" href="<?= WEB_ROOT . 'login#signup-links' ?>" role="button"><?= $ButtonBegin ?></a>
          </p>
        </div>
      </div>
      <div class="col-lg">
        <img class="d-lg-block mx-auto" id="heroBanner-img-delivery" src="<?=WEB_ROOT?>media/assets/delivering.png" alt="delivering">
      </div>
    </div>
  </div>
</section>


<!-- SECTION FOLLOW PACKAGE -->
<section id="followPackage-home" class="dark-gradient-section">
  <div class="container-xl">
    <div class="row">
      <div class="col-lg">
        <img class="d-lg-block mx-auto" id="followPackage-img-package" src="<?=WEB_ROOT?>media/assets/packageHedwige.png" alt="package">
      </div>
      <div class="col-lg">

        <div class="jumbotron bg-transparent">
          <h1 class="display-4"><?= $TitlePackage ?></h1>
          <p class="lead text-white"><?= $TextTrack ?></p>

          <div class="input-group input-group-lg mb-3">
            <input type="text" class="form-control" placeholder="<?= $InputPackage ?>" id="pkgInput">
            <div class="input-group-append">
              <button class="btn btn-warning" type="button" onclick="getPackage()">
                <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" fill="currentColor" class="bi bi-arrow-right-short" viewBox="0 0 16 16">
                  <path fill-rule="evenodd" d="M4 8a.5.5 0 0 1 .5-.5h5.793L8.146 5.354a.5.5 0 1 1 .708-.708l3 3a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708-.708L10.293 8.5H4.5A.5.5 0 0 1 4 8z"/>
                </svg>
              </button>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</section>


