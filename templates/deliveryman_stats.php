<?php $this->_js[] = 'deliveryman/stats' ; ?>

<div class="container-xl">
    <div class="row">
        <div class="col-lg">
            <h1><?= $lastname ; ?> / Statistiques</h1>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js"></script>


            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="packages-tab" data-toggle="tab" href="#packages" role="tab" aria-controls="packages" aria-selected="true">Colis</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="delivery-tab" data-toggle="tab" href="#delivery" role="tab" aria-controls="delivery" aria-selected="false">Livraisons</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="salary-tab" data-toggle="tab" href="#salary" role="tab" aria-controls="salary" aria-selected="false">Salaire</a>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="packages" role="tabpanel" aria-labelledby="packages-tab">
                    <div class="chart-container" style="position: relative; height:40vh; width:80vw">
                        <canvas id="packagesCanvas"></canvas>
                    </div>
                    <script>
                        const ctx = document.getElementById('packagesCanvas');
                        const chartPackages = new Chart(ctx, {
                            type: 'bar',
                            responsive: true,
                            data: {
                                labels: ['Novembre' ,'Décembre','Janvier', 'Fevrier', 'Mars'],
                                datasets: [{
                                    label: ['Nb de colis livrés', 'test'],
                                    data: [12, 19, 3, 5, 6],
                                    backgroundColor: [
                                        'rgba(255, 99, 132, 0.2)',
                                        'rgba(54, 162, 235, 0.2)',
                                        'rgba(255, 206, 86, 0.2)',
                                        'rgba(75, 192, 192, 0.2)',
                                        'rgba(153, 102, 255, 0.2)'
                                    ]
                                }]
                            },
                            options: {
                                scales: {
                                    yAxes: [{
                                        ticks: {
                                            beginAtZero: true
                                        }
                                    }]
                                }
                            }
                        });

                        chartPackages.canvas.parentNode.style.height = '400px';
                        chartPackages.canvas.parentNode.style.width = '600px';
                    </script>

                </div>
                <div class="tab-pane fade" id="delivery" role="tabpanel" aria-labelledby="delivery-tab">b</div>
                <div class="tab-pane fade" id="salary" role="tabpanel" aria-labelledby="salary-tab">c</div>
            </div>


            <script type="text/javascript" src="<?= WEB_ROOT . "media/script/deliveryman/stats.js" ?>"></script>
        </div>
    </div>
</div>