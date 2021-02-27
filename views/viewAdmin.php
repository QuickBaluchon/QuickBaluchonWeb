<?php

$this->_header = ROOT . '/templates/Front/header.php';
$this->_css = ['header', 'login'];
$this->_js = ['main', 'loginAdmin'];

?>

<!-- HERO BANNER LOGIN -->
<section id="heroBanner-login">
  <div class="container-xl">
    <div class="row">
      <div class="col-lg">

        <!-- FORM LOGIN-->
        <div class="jumbotron bg-white">
          <h1 class="display-4">Espace Admin</h1>

          <form onsubmit="return false">
            <div class="form-group row">
              <div class="col-sm-10">
                <input type="text" class="form-control" id="inputLogin" placeholder="Nom d'utilisateur">
              </div>
            </div>
            <div class="form-group row">
              <div class="col-sm-10">
                <input type="password" class="form-control" id="inputPwd" placeholder="Mot de passe">
              </div>
            </div>
            <div class="form-group row">
              <div class="col-sm-10">
                <button onclick="tryLogin('admin')" class="btn btn-round btn-primary">Se connecter</button>
              </div>
            </div>
          </form>

        </div>

      </div>

      <div class="col-7">
        <!-- IMAGE -->
        <img class="d-lg-block mx-auto" src="<?=WEB_ROOT?>media/assets/admin.png" alt="admin">
      </div>
    </div>
  </div>
</section>
