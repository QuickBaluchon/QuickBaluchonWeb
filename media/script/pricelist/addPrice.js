function addPrice() {
    let ids = ["maxWeight", "ExpressPrice", "StandardPrice" ,"applicationDate", "status" ];
    let values = getInputsValue(ids);
    console.log(values);
    let json = JSON.stringify( {
        maxWeight: values['maxWeight'],
        ExpressPrice : values['ExpressPrice'],
        StandardPrice : values['StandardPrice'],
        applicationDate: values['applicationDate'],
        status: values["status"]
    } );

    ajax("../api/pricelist", json, 'POST', redirect);
}

function redirect(response){
    document.location.href="/admin/pricelist";
}
