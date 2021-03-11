function employStaff(idStaff) {

    let json = JSON.stringify({
        id: idStaff,
        employed: 1
    });

    ajax('../api/admin/updateStaff', json, 'POST', redirect);
}

function refuse(idDeliveryman) {

    let delivery = document.getElementById(idDeliveryman);
    delivery.remove();

    let json = JSON.stringify({
        id: idDeliveryman,
        employed: null
    });

    ajax('../api/deliveryman/employ', json, 'POST', redirect);
}

function redirect(reponse) {
    document.location.href="http://localhost:8888/QuickBaluchonWeb/admin/employ";
}
