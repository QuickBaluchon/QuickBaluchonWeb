function tryLogin (idLogin, idPwd) {
    let inputLogin = document.getElementById(idLogin);
    let inputPwd = document.getElementById(idPwd);
    if( inputLogin && inputPwd ) {
        let valueLogin = inputLogin.value.trim();
        let valuePwd = inputPwd.value.trim();
        if( valueLogin.length > 0 && valuePwd.length > 0 ) {
            let json = JSON.stringify( { name: valueLogin, password: valuePwd } );
            ajax('api/client/login', json, 'POST', login);
        }else {
            // input empty

        }
    }
}

