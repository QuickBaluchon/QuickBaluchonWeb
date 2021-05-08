
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
            ajaxWithToken('../api/client/' + idClient, json, 'PATCH', updated)
        else return false;
    }
    else return false;
}

function updated() {
    document.location.reload();
}

