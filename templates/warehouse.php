<?php extract($this->_template) ; ?>

<section id="warehouse">
  <h1><?= $Title ?></h1>
  <p>adresse: <?= $details["address"]?></p>
  <p><?= $details["AvailableVolume"]?> m3 / <?= $details["volume"]?> dispo </p>
  <p><?= $deliveryman ?> <?= $TextDeliverymen ?></p>
</section>

<section class="mt-5">
  <h2><?= $TitleDanger ?></h2>
  <div class="container rounded border border-danger d-flex flex-wrap justify-content-between p-4">
    <div class="flex-auto">
      <strong><?= $LabelDelete ?></strong>
      <p class="mb-0"><?= $WarningDelete ?></p>
    </div>
    <div>
      <button type="button" onclick="updateWarehouse(<?= $id ?>)" class="btn btn-outline-danger"><?= $ButtonDelete ?></button>
    </div>
  </div>
</section>

<script src="<?=WEB_ROOT.'media/script/warehouse/updateWarehouse.js'?>"></script>