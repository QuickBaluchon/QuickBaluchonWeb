function getInputsValue(arrayId, trim = false) {
    if (!arrayId || !Array.isArray(arrayId))
        return -1

    if (typeof trim !== 'boolean')
        return -2

    let input;
    let values = [];
    for (let i = 0; i < arrayId.length; i++) {
        input = document.getElementById(arrayId[i]);
        if (input && input.tagName === 'INPUT') {
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
    request.onreadystatechange = function () {
        if (request.readyState === 4) {
            if (request.status === 200)
                callback(request.response,);
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

function getCookie(cname) {
    let name = cname + "=";
    let decodedCookie = decodeURIComponent(document.cookie);
    let ca = decodedCookie.split(';');
    for (let i = 0; i < ca.length; i++) {
        let c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}

function getIdClient() {
    let jwt = getCookie('access_token');
    let decode = jwtDecode(jwt);
    if (decode)
        return decode.playload.sub;
    else
        return false;
}

function jwtDecode(jwt) {
    if (jwt) {
        jwt = jwt.split('.');
        let jwtParts = ['header', 'playload'];
        let decode = {};
        for (let i = 0; i < 2; i++) {
            try {
                decode[jwtParts[i]] = JSON.parse(atob(jwt[i]));
            } catch (e) {
                return false
            }
        }
        return decode;
    }
    return false;
}