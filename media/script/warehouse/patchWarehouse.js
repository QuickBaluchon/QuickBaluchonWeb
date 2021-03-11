function patch (warehouse) {
    let inputs = getInputsValue(['address', 'volume', 'AvailableVolume'], true) ;
    let active = document.getElementById('active').checked == true ? 1 : 0 ;
    let json = JSON.stringify({
        address: inputs['address'],
        volume: inputs['volume'],
        AvailableVolume: inputs['AvailableVolume'],
        active: active
    }) ;
    ajax('/api/warehouse/' + warehouse, json, 'PATCH', patched, console.log) ;
}
function patched (text) {
    window.location.reload() ;
}