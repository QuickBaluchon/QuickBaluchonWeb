function updateWarehouse(id) {
    let warehouse = document.getElementById(id);
    warehouse.remove();
    let json = JSON.stringify({
        active: 0,
    });
    ajax('../api/warehouse/' + id, json, 'DELETE', hello);
}

function hello(reponse) {
    console.log("hello", reponse);
}
