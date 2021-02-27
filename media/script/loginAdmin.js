function tryLogin (collection, callbackError) {
    let inputLogin = document.getElementById('inputLogin');
    let inputPwd = document.getElementById('inputPwd');
    if( inputLogin && inputPwd ) {
        let valueLogin = inputLogin.value.trim();
        let valuePwd = inputPwd.value.trim();
        if( valueLogin.length > 0 && valuePwd.length > 0 ) {
            let json = JSON.stringify( { username: valueLogin, password: valuePwd } );
            ajax(`${collection}/login`, json, 'POST', login, callbackError);
        }else {
            // input empty
            console.log('Please enter your login details');
        }
    }
}