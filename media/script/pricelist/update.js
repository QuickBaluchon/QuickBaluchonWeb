function update() {

    let json = [];

    let ids =['ExpressPrice', 'StandardPrice'];

    let values = getInputsValue(ids, true);


    if( values < 0 ) // error codes
        return false;

    if ( values['inputPassword1'] !== values['inputPassword'] ) {
        console.log('Mots de passe différents');
        return ;
    } else {
        let json = JSON.stringify( {
            ExpressPrice: values['ExpressPrice'],
            StandardPrice : values['StandardPrice'],
        } );

        ajax('../api/pricelist/1', json, 'PATCH', hello);
    }
}





function hello(reponse) {
    console.log("hello", reponse);
}
