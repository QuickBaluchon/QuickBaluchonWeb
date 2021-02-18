function updateProfile() {
    let ids = ['inputEmail', 'inputPhone'];
    let values = getInputsValue(ids, true);
    if( values < 0 ) // error codes
        return false;

    let changes = {};
    if( values.inputEmail ) changes['email'] = values.inputEmail;
    if( values.inputPhone ) changes['phone'] = values.inputPhone;

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
    let ids = ['inputVolumeCar', 'inputRadius'];
    let values = getInputsValue(ids, true);
    if( values < 0 ) // error codes
        return false;

    if( values.inputVolumeCar.length === 0 || parseFloat( values.inputVolumeCar ) < 0.1) {
        console.log('Error volume');
        return false;
    }else if( values.inputRadius.length === 0 || parseInt( values.inputRadius ) < 1 ){
        console.log('Error radius');
        return false;
    }

    let changes = {};
    if( values.inputVolumeCar) changes['volumeCar'] = values.inputVolumeCar;
    if( values.inputRadius ) changes['radius'] = values.inputRadius;

    json = JSON.stringify( changes);
    if( json.length > 2 ) {
        let idDeliveryman = getIdClient();
        if( idDeliveryman )
            ajax(`../api/deliveryman/${idDeliveryman}`, json, 'PATCH', updated)
        else return false;
    }
    else return false;
}

function updated() {
    document.location.reload();
}