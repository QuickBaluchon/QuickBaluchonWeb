function signup() {
    let json = [];

    let ids =['inputLastName',
    'inputFirstName',
    'inputEmail',
    'inputPhone',
    'inputPassword1',
    'inputPassword2',
    'inputBIC',
    'inputRIB',
    'inputEntrepot',
    'inputRadius',
    'inputVolume'];

    let values = getInputsValue(ids, true);

    if( values < 0 ) // error codes
        return false;


    if ( values['inputPassword1'] !== values['inputPassword2'] ) {
        console.log('Mots de passe différents');
    } else {
        let json = JSON.stringify( {
            lastname: values['inputLastName'],
            firstname : values['inputFirstName'],
            phone : values['inputPhone'],
            email: values['inputEmail'],
            password: values['inputPassword2'],
            volumeCar: parseInt(values['inputVolume']),
            radius: parseInt(values['inputRadius']),
            IBAN : values['inputBIC'],
            wharehouse : parseInt(values['inputEntrepot']),
        } );

        ajax('../api/deliveryman/signup', json, 'POST', hello);
    }
}

function hello(reponse) {
    console.log("hello", reponse);
}
