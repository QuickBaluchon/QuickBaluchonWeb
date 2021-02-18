<?php
$this->_header = ROOT . '/templates/Front/header.php';
$this->_js = ['main', 'package'];
$this->_header = ROOT . '/templates/Front/header.php';

?>

<section id="heroBanner-login">
  <div class="container col-8 d-flex flex-column justify-content-center">
    <div class="row">
      <div class="col">

          <div class="jumbotron bg-white" id="jumbotron">
            <h1 class="display-4">Réception d'un colis</h1>
            <form>
                <div class="input-group mb-3">
                    <span class="input-group-text">Poids</span>
                    <input type="number" class="form-control" id="weight" value="<?= $data['weight'] ; ?>">
                    <span class="input-group-text">kg</span>
                </div>
                <div class="input-group mb-3">
                    <span class="input-group-text">Volume</span>
                    <input type="number" class="form-control" id="volume" value="<?= $data['volume'] ; ?>">
                    <span class="input-group-text">m³</span>
                </div>
                <div class="input-group mb-3">
                    <span class="input-group-text">Addresse de livraison</span>
                    <input type="text" class="form-control" rows="3" id="address" value="<?= $data['address'] ; ?>">
                </div>
                <div class="input-group mb-3">
                    <span class="input-group-text">Email du destinataire</span>
                    <input type="text" class="form-control" id="email" value="<?= $data['email'] ; ?>">
                </div>
                <div class="input-group mb-3">
                    <span class="input-group-text">Délai de livraision</span>
                    <input type="number" class="form-control" id="delay" value="<?= $data['delay'] ; ?>">
                    <span class="input-group-text">jours</span>
                </div>

                <button type="button" class="btn btn-primary btn-lg btn-block" onclick="recieve(<?= $data['id'] ; ?>)">Valider la réception</button>
            </div>
        </div>
    </div>
</section>
