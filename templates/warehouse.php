<?php
extract($this->_template) ;
$this->_js[] = 'warehouse/patchWarehouse';

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
        <button onclick="patch(<?= $warehouse ?>)" class="btn btn-primary"><?= $ButtonChanges ?></button>
    </form>
</section>

<section class="mt-5">
    <h2><?= $TitleDanger ?></h2>
    <?php if ($details['active'] == 1): ?>
    <div class="container rounded border border-danger d-flex flex-wrap justify-content-between p-4">
        <div class="flex-auto">
            <strong><?= $LabelDelete ?></strong>
            <p class="mb-0"><?= $WarningDelete ?></p>
        </div>
        <div>
            <?php if($details["AvailableVolume"] == $details["volume"]){ ?>
                <button type='button' onclick='updateWarehouse(<?= $id ?>, 0)' class='btn btn-outline-danger'><?= $ButtonDelete ?></button>
            <?php }else{?>
                <span>impossible de supprimer</span>
            <?php }?>
        </div>
    </div>
    <?php else: ?>
    <div class="container rounded border border-success d-flex flex-wrap justify-content-between p-4">
        <div class="flex-auto">
            <strong><?= $LabelReactivate ?></strong>
            <p class="mb-0"><?= $WarningReactivate ?></p>
        </div>
        <div>
            <button type='button' onclick='updateWarehouse(<?= $id ?>, 1)' class='btn btn-outline-success'><?= $ButtonReactivate ?></button>
        </div>
    </div>
    <?php endif; ?>
</section>

<script src="<?=WEB_ROOT.'media/script/warehouse/updateWarehouse.js'?>"></script>
