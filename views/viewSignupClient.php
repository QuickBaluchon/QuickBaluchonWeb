<?php
$this->_header = ROOT . '/templates/Front/header.php';
$this->_css = ['header', 'signup_client'];
?>

<!-- HERO BANNER LOGIN -->
<section id="heroBanner-signup">
  <div class="container-xl">
    <div class="row">
      <div class="col-lg">

        <!-- FORM LOGIN-->
        <div class="jumbotron bg-white">
          <h1 class="display-4">Inscription</h1>

          <form>
            <div class="form-group row">
              <div class="col-sm-10">
                <input type="text" class="form-control" id="inputEmail" placeholder="nom d'utilisateur">
              </div>
            </div>
            <div class="form-group row">
              <div class="col-sm-10">
                <input type="text" class="form-control" id="inputUrl" placeholder="site web">
              </div>
            </div>
            <div class="form-group row">
              <div class="col-sm-10">
                <input type="text" class="form-control" id="inputPaymentMethod" placeholder="méthode de paiement">
              </div>
            </div>
            <div class="form-group row">
              <div class="col-sm-10">
                <input type="password" class="form-control" id="inputPassword1" placeholder="mot de passe">
              </div>
            </div>
            <div class="form-group row">
              <div class="col-sm-10">
                <input type="password" class="form-control" id="inputPassword2" placeholder="mot de passe">
              </div>
            </div>
            <div class="form-group row">
              <div class="col-sm-10">
                <button type="submit" class="btn btn-round btn-primary">S'inscrire</button>
              </div>
            </div>
            <div class="form-group row">
              <div class="col-sm-10">
                <small class="form-text text-muted"><a href="#">Vous possédez déjà un compte ?</a></small>
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
