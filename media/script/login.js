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

function ajax(url, json, method, callback) {
    let request = new XMLHttpRequest();
    request.onreadystatechange = function() {
        if(request.readyState === 4) {
            if(request.status === 200)
                callback(request.response);
             else {
                // Error
                console.log(request.response);
            }
        }
    }
    request.open(method, url, true);
    request.setRequestHeader("Content-Type", "application/json; charset=utf-8");
    request.send(json);

}

function login(jwt) {
    jwt = JSON.parse(jwt);
    document.cookie = 'access_token=' + jwt.access_token;
    let url = window.location.href.replace('login', jwt.role + '/bills');
    window.location.href = url;
}