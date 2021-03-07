window.onload = function () {
    let json = JSON.stringify({
        stats: "package",
        deliveryman: 4
    }) ;
    console.log(json) ;
    ajax('../api/deliveryManStats/5', json, 'POST', displayStats, console.log) ;
}

function displayStats(stats) {
    let packages;
    try { packages = JSON.parse(stats); }
    catch (e) { return e }

    if( packages.length > 12 ) packages = packages.slice(0,11);
    let months = Object.keys(packages).reverse();
    let numbers = Object.values(packages).reverse();

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
                        beginAtZero: true
                    }
                }]
            }
        }
    });

    chartPackages.canvas.parentNode.style.height = '400px';
    chartPackages.canvas.parentNode.style.width = '600px';
}