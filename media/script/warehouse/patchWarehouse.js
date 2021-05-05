function patch (warehouse) {
    let inputs = getInputsValue(['address', 'volume', 'AvailableVolume'], true) ;
    let json = JSON.stringify({
        address: inputs['address'],
        volume: inputs['volume'],
        AvailableVolume: inputs['AvailableVolume']
    }) ;
    ajax('/api/warehouse/' + warehouse, json, 'PATCH', patched, console.log) ;
}
function patched (text) {
    window.location.reload() ;
}