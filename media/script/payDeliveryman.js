
function pay(idPayslip) {
    ajax('../api/payslip&id=' + idPayslip, null, 'GET', updated);
}

function updated(response) {

    result = JSON.parse(response);
    console.log(response);
    let json = JSON.stringify( {
        id: result[0]["id"],
        grossAmount : result[0]["grossAmount"],
        paid : 1
    } );
    ajax('/api/payslip', json, 'PATCH', reload);

}

function reload(){
    document.location.reload();
}
