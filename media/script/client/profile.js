
function updateProfile() {
    let ids = ['inputName', 'inputWebsite'];
    let values = getInputsValue(ids, true);
    if( values < 0 ) // error codes
        return false;

    let changes = {};
    for ( const idInput in values )
        if(values[idInput].length > 0) {
            let apiField = idInput.replace('input', '').toLowerCase();
            changes[apiField] = values[idInput];
        }

    json = JSON.stringify(changes);
    if( json.length > 2 ) {
        let idClient = getIdClient();
        if( idClient )
            ajax('../api/client/' + idClient, json, 'PATCH', updated)
        else return false;
    }
    else return false;
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
