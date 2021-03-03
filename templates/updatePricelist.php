<?php extract($this->_template) ; ?>

<div class="container-lg">
    <div class="row">
        <div class="col-lg">

            <div class="jumbotron bg-white mb-0">
                <h1 class="display-4"><?= $Title ?></h1>

                <form class="form-round">
                    <div class="form-group row">
                        <div class="col-sm-10">
                            <label for="inputWeight"><?= $LabelWeight ?></label>
                            <input type="number" min="0.1" step="0.01" class="form-control" id="inputWeight"
                                   value="<?= $values['maxWeight'] ?>" disabled="true">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-10">
                            <label for="inputExpress"><?= $LabelExpress ?></label>
                            <input type="number" min="0.1" step="0.01" class="form-control" id="ExpressPrice"
                                   value="<?= $values['ExpressPrice'] ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-10">
                            <label for="inputStandard"><?= $LabelStandard ?></label>
                            <input type="number" min="0.1" step="0.01" class="form-control" id="StandardPrice"
                                   value="<?= $values['StandardPrice'] ?>">
                        </div>
                    </div>
                    <hr>
                    <div class="form-group row">
                        <div class="col-sm-10">
                            <label for="inputStandard"><?= $LabelApplication ?></label>
                            <input type="date" class="form-control" id="inputDate">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-10">
                            <button type="button"  class="btn btn-round btn-primary" onclick="update(<?php echo $id ?>)"><?= $ButtonSave ?></button>
                        </div>
                    </div>
                </form>

            </div>
        </div>

        <div class="package-img col-5 d-flex flex-column justify-content-center">
            <!-- IMAGE -->
            <img class="d-lg-block mx-auto" style="display: none; width: inherit" src="<?=WEB_ROOT?>media/assets/package.png" alt="package">
        </div>
    </div>
</div>

<section class="mt-1">
    <h2><?= $TitleDanger ?></h2>
    <div class="rounded border border-danger d-flex flex-wrap justify-content-between p-4">
        <div class="flex-auto">
            <strong><?= $LabelDelete ?></strong>
            <p class="mb-0"><?= $WarningDelete ?></p>
        </div>
        <div>
            <button type="button" onclick="deletePrice(<?php echo $id ?>)" class="btn btn-outline-danger"><?= $ButtonDelete ?></button>
        </div>
    </div>
</section>

<script src="<?=WEB_ROOT.'media/script/pricelist/update.js'?>"></script>