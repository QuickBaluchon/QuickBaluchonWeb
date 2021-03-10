let wrong = document.getElementById('wrong');
let inputLogin = document.getElementById('inputLogin');
let inputPwd = document.getElementById('inputPwd');

function tryLogin (collection, callbackError) {

    if( inputLogin && inputPwd ) {
        let valueLogin = inputLogin.value.trim();
        let valuePwd = inputPwd.value.trim();
        if( valueLogin.length > 0 && valuePwd.length > 0 ) {
            let json = JSON.stringify( { username: valueLogin, password: valuePwd } );
            ajax(`${collection}/login`, json, 'POST', login, callbackError);
        }else {
            // input empty
            message(0)
        }
    }
}

function wrongCredential(response){
    if(response.status == 401){
        message(1);
    }
}


function message(code){
    let error = document.createElement("p");
    inputLogin.className = "form-control text-danger is-invalid";
    inputPwd.className = "form-control text-danger is-invalid";
    wrong.innerHTML = "";
    switch (code) {
        case 0:
            error.innerHTML = "ecrivez vos identifiants !!!!!!!";
            break;
        case 1:
            error.innerHTML = "Wrong credentials";
        break;
    }
    wrong.appendChild(error);

}
