let error = document.getElementById("wrong");
let inputLogin = document.getElementById('inputLogin');
let inputPwd = document.getElementById('inputPwd');



function tryLogin (collection, callbackError) {

    if( inputLogin && inputPwd ) {
        let valueLogin = inputLogin.value.trim();
        let valuePwd = inputPwd.value.trim();
        if( valueLogin.length > 0 && valuePwd.length > 0 ) {
            let json = JSON.stringify( { name: valueLogin, password: valuePwd } );
            ajax(`api/${collection}/login`, json, 'POST', login, callbackError);
        }else {
            // input empty
            wrong.innerHTML = 'Please enter your login details';
            error.appendChild(wrong);
        }
    }
}

function deliveryman(response) {
    if( response.status === 401 ) {
        tryLogin ('deliveryman', wrongCredentials);
    }
    else return false;
}

function wrongCredentials(response) {

    if( response.status === 401 ) {
        inputLogin.className = "form-control text-danger is-invalid";
        inputPwd.className = "form-control text-danger is-invalid";
        error.innerHTML = "";
        let wrong = document.createElement("p");
        wrong.innerHTML = "Wrong credentials";
        error.appendChild(wrong);
    }
    else return false;
}
