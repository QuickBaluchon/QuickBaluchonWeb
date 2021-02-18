<?php

$this->_header = ROOT . '/templates/Front/header.php';
$this->_css = ['header', 'signup_deliveryman'];
$this->_js = ['main','deliveryman/signup'];

?>


<!-- HERO BANNER LOGIN -->
<section id="signup">
  <div class="container-xl">
    <div class="row">
      <div class="col-lg">

        <!-- FORM LOGIN-->
        <div class="jumbotron bg-white">
          <h1 class="display-4">Inscription</h1>
          <hr class="my-4">
          <form onsubmit="return false">

            <!-- PERSONAL INFORMATIONS -->
            <h2 class="h3">Informations personnelles</h1>
            <div class="form-row">
              <div class="form-group col-md-6">
                <input type="text" class="form-control" id="inputLastName" placeholder="Nom">
              </div>
              <div class="form-group col-md-6">
                <input type="text" class="form-control" id="inputFirstName" placeholder="Prénom">
              </div>
            </div>

            <div class="form-row">
              <div class="form-group col-md-6">
                <input type="text" class="form-control" id="inputEmail" placeholder="E-mail">
              </div>
              <div class="form-group col-md-6">
                <input type="text" class="form-control" id="inputPhone" placeholder="Téléphone">
              </div>
            </div>

            <div class="form-row">
              <div class="form-group col-md-6">
                <input type="password" class="form-control" id="inputPassword1" placeholder="Mot de passe">
              </div>
              <div class="form-group col-md-6">
                <input type="password" class="form-control" id="inputPassword2" placeholder="Mot de passe">
              </div>
            </div>

            <hr class="my-4">
            <!-- BANKING INFORMATIONS -->
            <h2 class="h3">Informations bancaires</h1>
            <div class="form-row">
              <div class="form-group col-md-6">
                <input type="password" class="form-control" id="inputBIC" placeholder="BIC">
              </div>
              <div class="form-group col-md-6">
                <input type="password" class="form-control" id="inputRIB" placeholder="RIB">
              </div>
            </div>


            <hr class="my-4">
            <!-- BANKING INFORMATIONS -->
            <h2 class="h3">Informations de livraison</h1>
            <div class="form-row">
              <div class="form-group col-md-4">
                <select class="custom-select" id="inputEntrepot">
                  <option selected>Entrepôt</option>
                  <option value="1">Paris</option>
                  <option value="2">Nanterre</option>
                  <option value="3">Saint-Pierre</option>
                </select>
              </div>
              <div class="form-group col-md-4">
                <input type="number" min="1" class="form-control" id="inputRadius" placeholder="rayon (km)">
              </div>
              <div class="form-group col-md-4">
                <input type="number" min="1"  class="form-control" id="inputVolume" placeholder="volume (m3)">
              </div>
            </div>


            <hr class="my-4">
            <!-- FILES -->
            <h2 class="h3">Fichiers</h1>
            <div class="form-row">
              <div class="form-group col-md-6">
                <label for="inputLicense">Permis de conduire</label>
                <input type="file" class="form-control-file" id="fileLicense">
              </div>
              <div class="form-group col-md-6">
                <label for="fileRegistration">Carte grise</label>
                <input type="file" class="form-control-file" id="fileRegistration">
              </div>
            </div>



            <!-- BUTTON SIGNUP -->
            <hr class="my-4">
            <div class="form-group row">
              <div class="col-sm-10">
                <button  class="btn btn-round btn-primary" onclick="signup()">S'inscrire</button>
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

      <div class="col-5 d-flex flex-column justify-content-center">
        <!-- IMAGE -->
        <img class="d-lg-block mx-auto" src="../media/assets/signup_deliveryman.png" alt="delivering">
      </div>
    </div>
  </div>
</section>