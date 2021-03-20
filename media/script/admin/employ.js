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


function addStaff() {
    let ids = ["firstname", "lastname", "username", "inputEntrepot"];
    let values = getInputsValue(ids, true);

    let json = JSON.stringify({
        firstname: values["firstname"],
        lastname: values["lastname"],
        username: values["username"],
        warehouse: values["inputEntrepot"]
    });

    ajax('../api/admin/addStaff', json, 'PUT', redirect);
}

function redirect(reponse) {
    document.location.href="/admin/staff";
}
