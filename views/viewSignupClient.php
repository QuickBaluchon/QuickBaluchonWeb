<?php
$this->_header = ROOT . '/templates/Front/header.php';
$this->_css = ['header', 'signup_client'];
$this->_js = ['main', 'client/signup'];
extract($this->_content) ;
?>

<!-- HERO BANNER LOGIN -->
<section id="heroBanner-signup">
  <div class="container-xl">
    <div class="row">
      <div class="col-lg">

        <!-- FORM LOGIN-->
        <div class="jumbotron bg-white">
          <h1 class="display-4"><?= $Title ?></h1>

          <form onsubmit="return false">
            <div class="form-group row">
              <div class="col-sm-10">
                <input type="text" class="form-control" id="inputName" placeholder="<?= $InputUsername ?>">
              </div>
            </div>
            <div class="form-group row">
              <div class="col-sm-10">
                <input type="text" class="form-control" id="inputUrl" placeholder="<?= $InputWebsite ?>">
              </div>
            </div>
            <div class="form-group row">
              <div class="col-sm-10">
                <input type="password" class="form-control" id="inputPassword1" placeholder="<?= $InputPassword ?>">
              </div>
            </div>
            <div class="form-group row">
              <div class="col-sm-10">
                <input type="password" class="form-control" id="inputPassword2" placeholder="<?= $InputPassword ?>">
              </div>
            </div>
            <div class="form-group row">
              <div class="col-sm-10">
                <button onclick="trySignup()" type="submit" class="btn btn-round btn-primary"><?= $ButtonSignup ?></button>
              </div>
            </div>
            <div class="form-group row">
              <div class="col-sm-10">
                <small class="form-text text-muted"><a href="#"><?= $LinkAccount ?></a></small>
              </div>
            </div>
          </form>

        </div>

      </div>

      <div class="col-7">
        <!-- IMAGE -->
        <img class="d-lg-block mx-auto" src="<?=WEB_ROOT?>media/assets/security.png" alt="signup">
      </div>
    </div>
  </div>
</section>
