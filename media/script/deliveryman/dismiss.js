function dismissDeliveryman (id) {
    let json = JSON.stringify({
        id: id,
        employed: 0
    })
    ajax('/api/deliveryman/employ', json, 'PATCH', dismissed, console.log);
}

function dismissed (text) {
    window.location.reload();
}