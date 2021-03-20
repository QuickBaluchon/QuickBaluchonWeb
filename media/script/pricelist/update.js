function update(id) {
    let ids =['ExpressPrice', 'StandardPrice', 'inputDate'];

    let values = getInputsValue(ids, true);


    if( values < 0 ) // error codes
        return false;

        let json = JSON.stringify( {
            ExpressPrice: values['ExpressPrice'],
            StandardPrice : values['StandardPrice'],
            inputDate: values['inputDate'],
            status: 1
        } );

        ajax('../../api/pricelist/' + id, json, 'PATCH', hello);

}

function deletePrice(id) {
    let json = JSON.stringify( {
        status: 0,
    } );
    ajax('../../api/pricelist/' + id, json, 'DELETE', redirect);
}


function redirect(response){
    document.location.href="/admin/pricelist";
}

function hello(reponse) {
    console.log("hello", reponse);
}
