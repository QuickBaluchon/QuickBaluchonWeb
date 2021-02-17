
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
    ajax('../api/client/1', json, 'PATCH', updated);
}

function updated(response) {
    document.location.reload();
}

function updatePwd() {
    let ids = ['inputOldPassword', 'inputPassword'];
    let values = getInputsValue(ids, true);

}