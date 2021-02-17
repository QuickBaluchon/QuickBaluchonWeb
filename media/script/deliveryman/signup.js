function signup() {
    let json = [];

    let ids =['inputLastName',
    'inputFirstName',
    'inputEmail',
    'inputPhone',
    'inputPassword1',
    'inputPassword2',
    'inputEntrepot',
    'inputBIC',
    'inputRIB',
    'inputRadius',
    'inputVolume',
    'fileLicense',
    'fileRegistration'];

    let values = getInputsValue(ids, true);
console.log(values);
    if( values < 0 ) // error codes
        return false;


    if ( values['inputPassword1'] !== values['inputPassword2'] ) {
        console.log('Mots de passe différents');
    } else {
        let json = JSON.stringify( {
            lastname: values['inputLastName'],
            FirstName : values['inputFirstName'],
            phone : values['inputPhone'],
            email: values['inputEmail'],
            password: values['inputPassword2'],
            licenseImg : values['fileLicense'],
            registrationIMG : values['fileRegistration'],
            volumeCar: values['inputVolume'],
            radius: values['inputRadius'],
            IBAN : values['inputBIC'],
            wharehouse : values['inputEntrepot'],
        } );
        ajax('../api/deliveryman/signup', json, 'POST', signup);
    }



}
