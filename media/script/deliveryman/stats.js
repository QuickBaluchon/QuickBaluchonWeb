window.onload = function () {
    let kilometer;
    displayStatsPackages();
    fetchKilometers();

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
            datasets: [
                {
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

function fetchKilometers() {
    let json = JSON.stringify({
        stats: "km",
        deliveryman: 4
    }) ;
    ajax('../api/deliveryManStats/6', json, 'POST', fetchActivity, console.log) ;
}

function fetchActivity(data) {
    kilometer = data;

    let json = JSON.stringify({
        stats: "activity",
        deliveryman: 4
    }) ;
    ajax('../api/deliveryManStats/6', json, 'POST', displayStats, console.log) ;
}

function displayStats(activity) {
    let kms,hours;
    let months = [];
    let km = [];
    let hour = [];
    try {
        kms = JSON.parse(kilometer);
        hours = JSON.parse(activity);
    }
    catch (e) { return e }
    if( kms.length > 12 ) kms = kms.slice(0,11);

    for( let i = 0; i < 12; i++ ) {
        if( kms[i] ) {
            months.unshift(Object.keys(kms[i])[0]);
            km.unshift(Object.values(kms[i])[0])
            hour.unshift(Object.values(hours[i])[0])
        }
    }

    const ctx = document.getElementById('deliveriesCanvas');
    let deliveriesChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: months,
            datasets: [{
                label: 'Kms',
                yAxisID: 'Kms',
                data: km,
                backgroundColor: 'rgba(153, 102, 255, 0.2)'
            }, {
                label: 'Activité',
                yAxisID: 'Activity',
                data: hour,
                backgroundColor: 'rgba(255, 206, 86, 0.2)'
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    id: 'Kms',
                    type: 'linear',
                    position: 'left',
                    ticks: { stepSize: 1 }
                }, {
                    id: 'Activity',
                    type: 'linear',
                    position: 'right',
                    ticks: { stepSize: 1 }
                }]
            }
        }
    });

    deliveriesChart.canvas.parentNode.style.height = '400px';
    deliveriesChart.canvas.parentNode.style.width = '600px';
}