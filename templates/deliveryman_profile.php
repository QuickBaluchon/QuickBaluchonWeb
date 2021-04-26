<?php extract($this->_template) ; ?>

<div class="container-xl">
  <div class="row">
    <div class="col-lg">

      <h1><?= $lastname ?> / <?= $Title ; ?></h1>

      <ul class="nav nav-pills mb-3 mt-4" id="pills-tab" role="tablist">
        <li class="nav-item">
          <a class="nav-link active" id="pills-password-tab" data-toggle="pill" href="#pills-profile" role="tab" aria-controls="pills-home" aria-selected="true"><?= $LinkProfile ; ?></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" id="pills-car-tab" data-toggle="pill" href="#pills-car" role="tab" aria-controls="pills-profile" aria-selected="false"><?= $LinkVehicle ; ?></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#pills-password" role="tab" aria-controls="pills-profile" aria-selected="false"><?= $LinkPassword ; ?></a>
        </li>
      </ul>
      <div class="tab-content" id="pills-tabContent">
        <div class="tab-pane fade show active" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">

          <!-- content navpills profile -->
          <form onsubmit="return false" class="form-round col-lg">
            <div class="form-group">
              <label for="inputName"><?= $LabelName ; ?></label>
              <input type="text" class="form-control" id="inputFirstName" value="<?= $firstname ?>" disabled="true">
            </div>
            <div class="form-group">
              <label for="inputName"><?= $LabelSurname ; ?></label>
              <input type="text" class="form-control" id="inputLastName" value="<?= $lastname ?>" disabled="true">
            </div>
            <div class="form-group">
              <label for="inputEmail"><?= $LabelEmail ; ?></label>
              <input type="text" class="form-control" id="inputEmail" placeholder="<?= $email ?>">
            </div>
            <div class="form-group">
              <label for="inputPhone"><?= $LabelPhone ; ?></label>
              <input type="text" class="form-control" id="inputPhone" placeholder="<?= $phone ?>">
            </div>

            <button onclick="updateProfile()" type="button" class="btn btn-success"><?= $ButtonSave ; ?></button>
          </form>
        </div>

        <!-- content navpills password -->
        <div class="tab-pane fade" id="pills-password" role="tabpanel" aria-labelledby="pills-password-tab">
          <form onsubmit="return false" class="form-round col-lg">
            <div class="form-group">
              <label for="inputOldPassword"><?= $LabelPreviousPassword ; ?></label>
              <input type="password" class="form-control" id="inputOldPassword">
            </div>
            <div class="form-group">
              <label for="inputPassword"><?= $LabelNewPassword ; ?></label>
              <input type="password" class="form-control" id="inputPassword">
            </div>

            <button onclick="updatePwd('deliveryman')"  type="button" class="btn btn-success"><?= $ButtonSave ; ?></button>
          </form>

        </div>

        <div class="tab-pane fade" id="pills-car" role="tabpanel" aria-labelledby="pills-password-tab">
            <form class="" action="../api/deliveryman/register&id=<?= $_SESSION["id"] ?>&file=License" method="post" enctype="multipart/form-data">
                <div class="form-group">
                  <label for="inputLicense"><?= $LabelLicense ; ?></label>
                  <img style="max-width:100px" src="../license/<?= $licenseImg ; ?>"/>
                  <input type="file" name="fileLicense" class="form-control" id="inputLicense">
                  <input class="btn btn-primary" type="submit" value="envoyer">
                </div>
            </form>
            <form class="" action="../api/deliveryman/register&id=<?= $_SESSION["id"] ?>&file=Registration" method="post" enctype="multipart/form-data">
                <div class="form-group">
                  <label for="inputRegistration"><?= $LabelRegistration ; ?></label>
                  <img style="max-width:100px" src="../registration/<?= $_SESSION["id"] ; ?>"/>
                  <input type="file" name="fileRegistration" class="form-control" id="fileRegistration">
                  <input class="btn btn-primary" type="submit" value="envoyer">
                </div>
            </form>
          <form onsubmit="return false" class="form-round col-lg">
            <div class="form-group">
              <label for="inputVolumeCar"><?= $LabelVolume ; ?></label>
              <input type="number" min="0.1" step="0.1" class="form-control" id="inputVolumeCar" value="<?= $volumeCar ?>">
            </div>
            <div class="form-group">
              <label for="inputRadius"><?= $LabelRadius ; ?></label>
              <input type="number" min="1" class="form-control" id="inputRadius" value="<?= $radius ?>">
            </div>

            <button onclick="updateCar()" type="button" class="btn btn-success"><?= $ButtonSave ; ?></button>
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

<script src="<?= WEB_ROOT . 'media/script/deliveryman/profile.js' ?>"></script>
