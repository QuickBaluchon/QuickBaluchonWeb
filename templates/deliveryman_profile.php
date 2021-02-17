
<div class="container-xl">
  <div class="row">
    <div class="col-lg">

      <h1><?= $lastname ?> / Profil</h1>

      <ul class="nav nav-pills mb-3 mt-4" id="pills-tab" role="tablist">
        <li class="nav-item">
          <a class="nav-link active" id="pills-password-tab" data-toggle="pill" href="#pills-profile" role="tab" aria-controls="pills-home" aria-selected="true">Profil</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" id="pills-car-tab" data-toggle="pill" href="#pills-car" role="tab" aria-controls="pills-profile" aria-selected="false">Information car</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#pills-password" role="tab" aria-controls="pills-profile" aria-selected="false">Mot de passe</a>
        </li>
      </ul>
      <div class="tab-content" id="pills-tabContent">
        <div class="tab-pane fade show active" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">

          <!-- content navpills profile -->
          <form class="form-round col-lg">
            <div class="form-group">
              <label for="inputName">Prénom</label>
              <input type="text" class="form-control" id="inputName" value="<?= $firstname ?>" disabled="true">
            </div>
            <div class="form-group">
              <label for="inputName">Nom</label>
              <input type="text" class="form-control" id="inputName" value="<?= $lastname ?>" disabled="true">
            </div>
            <div class="form-group">
              <label for="inputWebsite">Email</label>
              <input type="text" class="form-control" id="inputWebsite" placeholder="<?= $email ?>">
            </div>
            <div class="form-group">
              <label for="inputWebsite">numéro de téléphone</label>
              <input type="text" class="form-control" id="inputWebsite" placeholder="<?= $phone ?>">
            </div>


            <button type="button" class="btn btn-success">Sauvegarder</button>
          </form>
        </div>

        <!-- content navpills password -->
        <div class="tab-pane fade" id="pills-password" role="tabpanel" aria-labelledby="pills-password-tab">
          <form class="form-round col-lg">
            <div class="form-group">
              <label for="inputOldPassword">Ancien mot de passe</label>
              <input type="password" class="form-control" id="inputOldPassword">
            </div>
            <div class="form-group">
              <label for="inputPassword">Mot de passe</label>
              <input type="password" class="form-control" id="inputPassword">
            </div>

            <button type="button" class="btn btn-success">Changer</button>
          </form>

        </div>

        <div class="tab-pane fade" id="pills-car" role="tabpanel" aria-labelledby="pills-password-tab">
          <form class="form-round col-lg">
            <div class="form-group">
              <label for="inputOldPassword">License</label>
              <input type="text" class="form-control" id="inputOldPassword" value="license: <?= $licenseImg ?>" disabled="true">
            </div>
            <div class="form-group">
              <label for="inputOldPassword">Registration</label>
              <input type="text" class="form-control" id="inputOldPassword" value="registration: <?= $registrationIMG ?>" disabled="true">
            </div>
            <div class="form-group">
              <label for="inputOldPassword">Volume</label>
              <input type="number" min="0.1" step="0.1" class="form-control" id="inputOldPassword" value="<?= $volumeCar ?>">
            </div>
            <div class="form-group">
              <label for="inputOldPassword">rayon de livraison</label>
              <input type="number" min="1" class="form-control" id="inputOldPassword" value="<?= $radius ?>">
            </div>

            <button type="button" class="btn btn-success">Changer</button>
          </form>

        </div>
      </div>

    </div>

    <div class="col-6">
      <!-- IMAGE -->
      <img class="d-xl-block mx-auto mt-3 visual" src="<?=WEB_ROOT?>media/assets/profile.png" alt="profile">
    </div>
  </div>
</div>
