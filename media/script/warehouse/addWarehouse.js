function addWarehouse() {
    let ids = ["address", "volume"];
    let values = getInputsValue(ids);

    if( values < 0 ) // error codes
        return false;

    let json = JSON.stringify( {
        address: values['address'],
        volume : values['volume']
    } );

    ajax('../api/warehouse', json, 'POST', redirect);
}


function redirect(response) {
    document.location.href="/admin/warehouses";
}
