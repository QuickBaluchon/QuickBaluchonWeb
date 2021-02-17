
function updateProfile() {
    let ids = ['inputName', 'inputWebsite'];
    let values = getInputsValue(ids, true);
    if( values < 0 ) // error codes
        return false;

    let changes = {};
    for ( const id in values )
        if(values[id].length > 0) {
            let newid = id.replace('input', '').toLowerCase();
            changes[newid] = values[id];
        }

    json = JSON.stringify(changes);
    let idClient = getIdClient();
    //ajax('../api/client/1', json, 'PATCH', updated);
}

function updated(response) {
    document.location.reload();
}

function updatePwd() {
    let ids = ['inputOldPassword', 'inputPassword'];
    let values = getInputsValue(ids, true);
    if( values < 0 ) // error codes
        return false;
    if( values['inputOldPassword'] && values['inputPassword'] ) {
        json = JSON.stringify( {
            oldpassword : values['inputOldPassword'],
            password : values['inputPassword']
        } );
        //ajax('../api/client/1', json, 'PATCH', updated);

    } else {
        console.log('Error params');
        return false;
    }


}
