<?php

$this->_header = ROOT . '/templates/Front/header.php';
$this->_css = ['header', 'signup_deliveryman'];
$this->_js = ['main','deliveryman/signup'];
extract($this->_content) ;
?>


<!-- HERO BANNER LOGIN -->
<section id="signup">
    <div class="container-xl">
        <div class="row">
            <div class="col-lg">

                <!-- FORM LOGIN-->
                <div class="jumbotron bg-white">
                    <h1 class="display-4"><?= $Title ?></h1>
                    <hr class="my-4">
                    <form method="POST" action="../api/deliveryman/register" id="form" onsubmit="return false" enctype="multipart/form-data">

                        <!-- PERSONAL INFORMATIONS -->
                        <h2 class="h3"><?= $TitlePersonal ?></h1>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <input type="text" class="form-control" id="inputLastName" placeholder="<?= $InputSurname ?>">
                                </div>
                                <div class="form-group col-md-6">
                                    <input type="text" class="form-control" id="inputFirstName" placeholder="<?= $InputName ?>">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <input type="text" class="form-control" id="inputEmail" placeholder="<?= $InputEmail ?>">
                                </div>
                                <div class="form-group col-md-6">
                                    <input type="text" class="form-control" id="inputPhone" placeholder="<?= $InputPhone ?>">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <input type="password" class="form-control" id="inputPassword1" placeholder="<?= $InputPassword ?>">
                                </div>
                                <div class="form-group col-md-6">
                                    <input type="password" class="form-control" id="inputPassword" placeholder="<?= $InputPassword ?>">
                                </div>
                            </div>

                            <hr class="my-4">
                            <!-- BANKING INFORMATIONS -->
                            <h2 class="h3"><?= $TitleBank ?></h1>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <input type="password" class="form-control" id="inputBIC" placeholder="<?= $InputBIC ?>">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <input type="password" class="form-control" id="inputRIB" placeholder="<?= $InputRIB ?>">
                                    </div>
                                </div>


                                <hr class="my-4">
                                <!-- BANKING INFORMATIONS -->
                                <h2 class="h3"><?= $TitleDelivery ?></h1>
                                    <div class="form-row">
                                        <div class="form-group col-md-4">
                                            <select class="custom-select" id="inputEntrepot">
                                                <option selected><?= $LabelWarehouse ?></option>
                                                <?php foreach ($options as $option) {
                                                    echo "<option>" . $option . "</option>";
                                                } ?>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <input type="number" min="1" class="form-control" id="inputRadius" placeholder="<?= $InputRadius ?>">
                                        </div>
                                        <div class="form-group col-md-4">
                                            <input type="number" min="1"  class="form-control" id="inputVolume" placeholder="<?= $InputVolume ?>">
                                        </div>
                                    </div>


                                    <hr class="my-4">
                                    <!-- FILES -->

                                        <h2 class="h3"><?= $TitleFiles ?></h1>
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="inputLicense"><?= $LabelLicense ?></label>
                                                <input type="file" name="fileLicense" class="form-control-file" id="fileLicense">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="fileRegistration"><?= $LabelRegistration ?></label>
                                                <input type="file" name="fileRegistration" class="form-control-file" id="fileRegistration">
                                            </div>
                                        </div>

                                        <!-- BUTTON SIGNUP -->
                                        <hr class="my-4">
                                        <div class="form-group row">
                                            <div class="col-sm-10">
                                                <input class="btn btn-round btn-primary" type="submit" onclick="hello()" value="<?= $ButtonRegister ?>"></input>
                                            </div>
                                        </div>
                                    </form>
                                        <div class="form-group row">
                                            <div class="col-sm-10">
                                                <small class="form-text text-muted"><a href="#"><?= $LinkLogin ?></a></small>
                                            </div>
                                        </div>


                </div>

            </div>

            <div class="col-5 d-flex flex-column justify-content-center">
                <!-- IMAGE -->
                <img class="d-lg-block mx-auto" src="../media/assets/signup_deliveryman.png" alt="delivering">
            </div>
        </div>
    </div>
</section>
