function employStaff(idStaff) {

    let json = JSON.stringify({
        id: idStaff,
        employed: 1
    });

    ajax('../api/admin/updateStaff', json, 'POST', redirect);
}

function refuseStaff(idStaff) {

    let json = JSON.stringify({
        id: idStaff,
        employed: 0
    });

    ajax('../api/admin/updateStaff', json, 'POST', redirect);
}

function redirect(reponse) {
    document.location.href="http://localhost:8888/QuickBaluchonWeb/admin/staff";
}
