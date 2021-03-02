<?php $this->_js[] = 'deliveryman/stats' ; ?>

<div class="container-xl">
    <div class="row">
        <div class="col-lg">
            <h1><?= $lastname ; ?> / Statistiques</h1>

            <ul class="nav nav-tabs" id="statTabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#packages" role="tab" aria-controls="home" aria-selected="true">Colis</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#deliveries" role="tab" aria-controls="home" aria-selected="true">Livraisons</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#pay" role="tab" aria-controls="home" aria-selected="true">Salaire</a>
                </li>
            </ul>
            <div class="tab-content mt-3" id="myTabContent">
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <script type="text/javascript" src="<?= WEB_ROOT . "media/script/deliveryman/stats.js" ; ?>"></script>

                    <div id="chartContainer" style="height: 300px; width: 100%;"></div>
                    <script src="https://canvasjs.com/assets/script/jquery-1.11.1.min.js"></script>
                    <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>


                </div>
                <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">Toi</div>
                <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">Comment va</div>
            </div>
        </div>
    </div>
</div>