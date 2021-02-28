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
                    <script>
                        window.onload = function() {

                            var dataPoints = [
                                {
                                    x: "2021-01",
                                    y: 32
                                },
                                {
                                    x: "2021-02",
                                    y: 36
                                },
                                {
                                    x: "2021-03",
                                    y: 32
                                }
                            ];

                            let chart = new CanvasJS.Chart("chartContainer", {
                                animationEnabled: true,
                                theme: "light2",
                                title: {
                                    text: "Colis livrés par mois"
                                },
                                axisY: {
                                    title: "Nombre",
                                    titleFontSize: 24,
                                    includeZero: true
                                },
                                data: [{
                                    type: "column",
                                    yValueFormatString: "#,### Units",
                                    dataPoints: dataPoints
                                }]
                            });

                            chart.render();
                        }
                    </script>
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