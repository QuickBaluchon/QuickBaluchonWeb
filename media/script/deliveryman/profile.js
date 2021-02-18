function updateProfile() {
    let ids = ['inputEmail', 'inputPhone'];
    let values = getInputsValue(ids, true);
    if( values < 0 ) // error codes
        return false;

    let changes = {};
    if( values['inputEmail'] ) changes['email'] = values['inputEmail'];
    if( values['inputPhone'] ) changes['phone'] = values['inputPhone'];

    json = JSON.stringify(changes);
    if( json.length > 2 ) {
        let idDeliveryman = getIdClient();
        if( idDeliveryman )
            ajax(`../api/deliveryman/${idDeliveryman}`, json, 'PATCH', updated)
        else return false;
    }
    else return false;
}

function updateCar() {
    console.log('car');
}

function updatePwd() {
    console.log('pwd');
}
function updated(response) {
    console.log(response);
}