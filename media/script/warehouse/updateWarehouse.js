function updateWarehouse(id, active) {
    let json = JSON.stringify({
        active: active,
    });
    ajax('../../api/warehouse/' + id, json, 'DELETE', redirect);
}

function redirect(reponse) {
    document.location.href="/admin/warehouses";
}
