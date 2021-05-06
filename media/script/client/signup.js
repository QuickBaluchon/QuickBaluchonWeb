function trySignup() {
    let ids = ['inputName', 'inputUrl', 'inputPassword1', 'inputPassword2'];
    let values = getInputsValue(ids, true);
    if( values < 0 ) // error codes
        return false;

    for ( const id in values )
        if(values[id].length < 1) {
            return 'UN INPUT VIDE';
        }

    if ( values['inputPassword1'] !== values['inputPassword2'] ) {
        console.log('Mots de passe différents');
    } else {
        let json = JSON.stringify( {
            name: values['inputName'],
            website : values['inputUrl'],
            password: values['inputPassword2']
        } );
        ajax('../api/client/signup', json, 'POST', signup);
    }

    function signup(jwt) {
        login(jwt, 'client/signup', '/profile');
    }

}
