function employ(idDeliveryman) {

    let delivery = document.getElementById(idDeliveryman);
    delivery.remove();

    let json = JSON.stringify({
        id: idDeliveryman,
        employed: 1
    });

    ajax('../api/deliveryman/employ', json, 'POST', hello);
}

function hello(reponse) {
    console.log("hello", reponse);
}
