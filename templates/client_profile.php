<?php extract($this->_template) ; ?>

<div class="container-xl">
  <div class="row">
    <div class="col-lg">

      <h1><?= $name ?> / <?= $Title ?></h1>

      <ul class="nav nav-pills mb-3 mt-4" id="pills-tab" role="tablist">
        <li class="nav-item">
          <a class="nav-link active" id="pills-password-tab" data-toggle="pill" href="#pills-profile" role="tab" aria-controls="pills-home" aria-selected="true"><?= $LinkProfile ?></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#pills-password" role="tab" aria-controls="pills-profile" aria-selected="false"><?= $LinkPassword ?></a>
        </li>
      </ul>
      <div class="tab-content" id="pills-tabContent">
        <div class="tab-pane fade show active" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">

          <!-- content navpills profile -->
          <form class="form-round col-lg" onsubmit="return false">
            <div class="form-group">
              <label for="inputName"><?= $LabelUsername ?></label>
              <input type="text" class="form-control" id="inputName" placeholder="<?= $name ?>">
            </div>
            <div class="form-group">
              <label for="inputWebsite"><?= $LabelWebsite ?></label>
              <input type="text" class="form-control" id="inputWebsite" placeholder="<?= $website ?>">
            </div>

            <button onclick="updateProfile()" type="button" class="btn btn-success"><?= $ButtonSave ?></button>
          </form>

        </div>
        <div class="tab-pane fade" id="pills-password" role="tabpanel" aria-labelledby="pills-password-tab">

          <!-- content navpills password -->
          <form class="form-round col-lg" onsubmit="return false">
            <div class="form-group">
              <label for="inputOldPassword"><?= $LabelPreviousPassword ?></label>
              <input type="password" class="form-control" id="inputOldPassword">
            </div>
            <div class="form-group">
              <label for="inputPassword"><?= $LabelNewPassword ?></label>
              <input type="password" class="form-control" id="inputPassword">
            </div>

            <button onclick="updatePwd('client')" type="button" class="btn btn-success"><?= $ButtonSave ?></button>
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

<script src="<?= WEB_ROOT . 'media/script/client/profile.js' ?>"></script>
