window.onload = function () {
    displayStatsPackages();
    displayStatsDeliveries();

}

function displayStatsPackages(stats) {

    if( !stats ) {
        let json = JSON.stringify({
            stats: "package",
            deliveryman: 4
        }) ;
        ajax('../api/deliveryManStats/6', json, 'POST', displayStatsPackages, console.log) ;
    }

    let packages;
    let months = [];
    let numbers = [];
    try { packages = JSON.parse(stats); }
    catch (e) { return e }
    if( packages.length > 12 ) packages = packages.slice(0,11);

    for( let i = 0; i < 12; i++ ) {
        if( packages[i] ) {
            months.unshift(Object.keys(packages[i])[0]);
            numbers.unshift(Object.values(packages[i])[0])
        }
    }

    const ctx = document.getElementById('packagesCanvas');
    const chartPackages = new Chart(ctx, {
        type: 'bar',
        responsive: true,
        data: {
            labels: months,
            datasets: [{
                label: 'Nb de colis livrés',
                data: numbers,
                backgroundColor: 'rgba(153, 102, 255, 0.2)'
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true,
                        stepSize: 1
                    }
                }]
            }
        }
    });

    chartPackages.canvas.parentNode.style.height = '400px';
    chartPackages.canvas.parentNode.style.width = '600px';
}

function displayStatsDeliveries(stats) {

    if( !stats ) {
        let json = JSON.stringify({
            stats: "km",
            deliveryman: 4
        }) ;
        ajax('../api/deliveryManStats/6', json, 'POST', displayStatsDeliveries, console.log) ;
        return;
    }
    let kms;
    let months = [];
    let numbers = [];
    try { kms = JSON.parse(stats); }
    catch (e) { return e }
    if( kms.length > 12 ) kms = kms.slice(0,11);

    for( let i = 0; i < 12; i++ ) {
        if( kms[i] ) {
            months.unshift(Object.keys(kms[i])[0]);
            numbers.unshift(Object.values(kms[i])[0])
        }
    }

    const ctx = document.getElementById('deliveriesCanvas');

    const chartPackages = new Chart(ctx, {
        type: 'line',
        responsive: true,
        data:  {
            labels: months,
            datasets: [{
                label: 'Kms parcourus',
                data: numbers,
                backgroundColor: 'rgba(153, 102, 255, 0.2)'
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
}