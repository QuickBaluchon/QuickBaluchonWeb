
function pay(idPayslip) {
    token = getCookie("access_token");
    idAdmin = getIdClient(token);

    let json = JSON.stringify( {
        idPayslip: idPayslip,
        idAdmin: idAdmin,
        paid : 1
    } );
    ajax('/api/payslip', json, 'PATCH', reload);
}


function reload(){
    document.location.reload();
}
