<?php
extract($this->_template) ;
$this->_js[] = 'warehouse/patchWarehouse'
?>

<section id="warehouse">
    <h1><?= $Title ?></h1>
    <p class="mt-3"><?= $deliveryman ?> <?= $TextDeliverymen ?></p>
    <form class="mt-3" onsubmit="">
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text"><?= $LabelAddress ?></span>
            </div>
            <input class="form-control" id="address" value="<?= $details["address"]?>" placeholder="<?= $LabelVolume ?>">
            <div class="input-group-prepend">
                <span class="input-group-text">/</span>
            </div>
        </div>
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text"><?= $LabelVolume ?></span>
            </div>
            <input class="form-control" id="AvailableVolume" value="<?= $details["AvailableVolume"]?>" placeholder="<?= $LabelVolume ?>">
            <div class="input-group-prepend">
                <span class="input-group-text">/</span>
            </div>
            <input class="form-control" id="volume" value="<?= $details["volume"]?>" placeholder="<?= $LabelVolume ?>">
            <div class="input-group-append">
                <span class="input-group-text">m3</span>
            </div>
        </div>
        <div class="form-group form-check">
            <input type="checkbox" class="form-check-input" id="active" <?= $details['active'] != 0 ? "checked" : "" ?>>
            <label class="form-check-label" for="active"><?= $LabelActive ?></label>
        </div>
        <button onclick="patch(<?= $warehouse ?>)" class="btn btn-primary"><?= $ButtonChanges ?></button>
    </form>
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
