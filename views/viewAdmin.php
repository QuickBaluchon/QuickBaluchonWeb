<?php

$this->_header = ROOT . '/templates/Front/header.php';
$this->_css = ['header', 'login'];
$this->_js = ['main', 'loginAdmin'];
extract($this->_content) ;

?>

<!-- HERO BANNER LOGIN -->
<section id="heroBanner-login">
  <div class="container-xl">
    <div class="row">
      <div class="col-lg">

        <!-- FORM LOGIN-->
        <div class="jumbotron bg-white">
          <h1 class="display-4"><?= $Title ?></h1>

          <form onsubmit="return false">
            <div class="form-group row">
              <div class="col-sm-10">
                <input type="text" class="form-control" id="inputLogin" placeholder="<?= $InputUsername ?>">
              </div>
            </div>
            <div class="form-group row">
              <div class="col-sm-10">
                <input type="password" class="form-control" id="inputPwd" placeholder="<?= $InputPassword ?>">
              </div>
            </div>
            <div class="form-group row">
              <div class="col-sm-10">
                <button onclick="tryLogin('admin', wrongCredential)" class="btn btn-round btn-primary"><?= $ButtonConnect ?></button>
              </div>
            </div>
          </form>
          <div id="wrong"></div>
        </div>

      </div>

      <div class="col-7">
        <!-- IMAGE -->
        <img class="d-lg-block mx-auto" src="<?=WEB_ROOT?>media/assets/admin.png" alt="admin">
      </div>
    </div>
  </div>
</section>
