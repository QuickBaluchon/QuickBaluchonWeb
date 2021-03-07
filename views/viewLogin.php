<?php
if ( isset($_SESSION['id'])){
  header('location: '.WEB_ROOT.'client/bills');
}

$this->_header = ROOT . '/templates/Front/header.php';
$this->_css = ['header', 'login'];
$this->_js = ['main','login'];
extract($this->_content) ;
?>

<!-- HERO BANNER LOGIN -->
<section id="heroBanner-login">
  <div class="container-xl">
    <div class="row">
      <div class="col-lg">

        <!-- FORM LOGIN-->
        <div class="jumbotron bg-white">
          <h1 class="display-4"><?= $TitleLogin ?></h1>

          <form onsubmit="return false">
            <div class="form-group row">
              <div class="col-sm-10">
                <input type="text" class="form-control" id="inputLogin" placeholder="<?= $InputLogin ?>">
              </div>
            </div>
            <div class="form-group row">
              <div class="col-sm-10">
                <input type="password" class="form-control" id="inputPwd" placeholder="<?= $InputPassword ?>">
              </div>
            </div>
            <div class="form-group row">
              <div class="col-sm-10">
                <button onclick="tryLogin('client', deliveryman)" class="btn btn-round btn-primary"><?= $ButtonLogin ?></button>
              </div>
            </div>
            <div class="form-group row">
              <div class="col-sm-10">
                <small class="form-text text-muted"><a href="#signup-links"><?= $LinkAccount ?></a></small>
              </div>
            </div>
          </form>

        </div>

      </div>

      <div class="col-7">
        <!-- IMAGE -->
        <img class="d-lg-block mx-auto" src="<?=WEB_ROOT?>media/assets/ordering.png" alt="delivering">
      </div>
    </div>
  </div>
</section>


<!-- SECTION SIGN UP -->
<section id="signup-links" class="dark-gradient-section">
  <div class="container-xl">
    <div class="row">

      <!-- IMAGE -->
      <div class="col-lg">
        <img class="d-lg-block mx-auto" src="<?=WEB_ROOT?>media/assets/truck.png" alt="package">
      </div>

      <!-- LINKS -->
      <div class="col-lg">
        <div class="jumbotron bg-transparent">
          <h1 class="display-4 text-white"><?= $TitleSignup ?></h1>

          <a href="<?= WEB_ROOT . 'client/signup' ?>">
            <div class="input-group input-group-lg mb-3">
              <div class="input-group-prepend">
                <span class="input-group-text"><?= $TextClient ?></span>
              </div>
              <div class="input-group-append">
                <button class="btn btn-warning" type="button"><svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" fill="currentColor" class="bi bi-arrow-right-short" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M4 8a.5.5 0 0 1 .5-.5h5.793L8.146 5.354a.5.5 0 1 1 .708-.708l3 3a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708-.708L10.293 8.5H4.5A.5.5 0 0 1 4 8z"/>
                  </svg>
                </button>
              </div>
            </div>
          </a>

          <a href="<?= WEB_ROOT . 'deliveryman/signup' ?>">
            <div class="input-group input-group-lg mb-3">
              <div class="input-group-prepend">
                <span class="input-group-text"><?= $TextDeliveryman ?></span>
              </div>
              <div class="input-group-append">
                <button class="btn btn-success" type="button"><svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" fill="currentColor" class="bi bi-arrow-right-short" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M4 8a.5.5 0 0 1 .5-.5h5.793L8.146 5.354a.5.5 0 1 1 .708-.708l3 3a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708-.708L10.293 8.5H4.5A.5.5 0 0 1 4 8z"/>
                  </svg>
                </button>
              </div>
            </div>
          </a>

        </div>

      </div>
    </div>
  </div>
</section>
