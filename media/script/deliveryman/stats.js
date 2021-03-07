window.onload = function () {
    let json = JSON.stringify({
        stats: "package",
        deliveryman: 4
    }) ;
    console.log(json) ;
    ajax('../api/deliveryManStats/5', json, 'POST', displayStats, console.log) ;
}