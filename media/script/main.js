function getInputsValue(arrayId, trim = false) {
    if (!arrayId || !Array.isArray(arrayId))
        return -1

    if (typeof trim !== 'boolean')
        return -2

    let input;
    let values = [];
    for (let i = 0; i < arrayId.length; i++) {
        input = document.getElementById(arrayId[i]);
        if( input && (input.tagName === 'INPUT' || input.tagName === 'SELECT')) {
            values[arrayId[i]] = trim === false ? input.value : input.value.trim();
        } else
            return -3;
    }
    return values;
}

function login(jwt, location, next) {
    try {
        jwt = JSON.parse(jwt)
    } catch (e) {
        console.log(e);
        return e
    }

    if (!location || !next) {
        location = 'login';
        next = '/bills';
    }
    document.cookie = 'access_token=' + jwt.access_token;
    let redirect = window.location.href.replace(location, jwt.role + next);
    window.location.href = redirect;

}

function ajax(url, json, method, callback) {
    let request = new XMLHttpRequest();
    request.onreadystatechange = function() {
        if(request.readyState === 4) {
            if(request.status === 200)
                callback(request.response);
            else {
                // Error
                console.log(request.status + ' : ' + request.response);
            }
        }
    }
    request.open(method, url, true);
    request.setRequestHeader("Content-Type", "application/json; charset=utf-8");
    request.send(json);

}
