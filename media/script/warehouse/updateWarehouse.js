function updateWarehouse(id) {
    let json = JSON.stringify({
        active: 0,
    });
    ajax('../../api/warehouse/' + id, json, 'DELETE', redirect);
}

function redirect(reponse) {
    document.location.href="http://localhost:8888/QuickBaluchonWeb/admin/warehouses";
}
