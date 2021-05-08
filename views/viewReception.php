<?php
$this->_header = ROOT . '/templates/Front/header.php';
$this->_js = ['main', 'package'];
$this->_header = ROOT . '/templates/Front/header.php';
extract($this->_content) ;
if (isset($data['status']) && $data['status'] != 0) {
    $formEnd = '<div class="alert alert-info">' . $Messages[$data['status']];
    $formEnd .= isset($data['dateDelivery']) && $data['status'] == 3 ? ' ' . $data['dateDelivery'] : '';
    $formEnd .= '</div>';
    $formEnd .= isset($data['signature']) && $data['status'] == 3 ?
        '<div class="card" style="width: 18rem;"><div class="card-body"><img src="' . $data['signature'] . '"/></div></div>'
        : '';
} else {
    $formEnd = '<button type="button" class="btn btn-primary btn-lg btn-block" onclick="recieve(' . $data['id'] . ')">' . $ButtonSave. '</button>' ;
}
?>

<section id="heroBanner-login">
    <div class="container col-8 d-flex flex-column justify-content-center">
        <div class="row">
            <div class="col">

                <div class="jumbotron bg-white" id="jumbotron">
                    <h1 class="display-4"><?= $Title ?> <?= $data['id'] ?></h1>
                    <hr>

                    <h6><a href="/client/profile/<?= $data['client'] ?>"><?= $Sender ?></a></h6>
                    <hr>

                    <form>
                        <div class="input-group mb-3">
                            <span class="input-group-text"><?= $LabelName ?></span>
                            <input type="text" class="form-control" value="<?= $data['nameRecipient'] ; ?>">
                        </div>
                        <div class="input-group mb-3">
                            <span class="input-group-text"><?= $LabelWeight ?></span>
                            <input type="number" class="form-control" id="weight" value="<?= $data['weight'] ; ?>">
                            <span class="input-group-text">kg</span>
                        </div>
                        <div class="input-group mb-3">
                            <span class="input-group-text"><?= $LabelVolume ?></span>
                            <input type="number" class="form-control" id="volume" value="<?= $data['volume'] ; ?>">
                            <span class="input-group-text">m³</span>
                        </div>
                        <div class="input-group mb-3">
                            <span class="input-group-text"><?= $LabelAddress ?></span>
                            <input type="text" class="form-control" rows="3" id="address" value="<?= $data['address'] ; ?>">
                        </div>
                        <div class="input-group mb-3">
                            <span class="input-group-text"><?= $LabelEmail ?></span>
                            <input type="text" class="form-control" id="email" value="<?= $data['email'] ; ?>">
                        </div>
                        <div class="input-group mb-3">
                            <span class="input-group-text"><?= $LabelDelay ?></span>
                            <input type="number" class="form-control" id="delay" value="<?= $data['delay'] ; ?>">
                            <span class="input-group-text">jours</span>
                        </div>
                        <?= $formEnd ?>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
