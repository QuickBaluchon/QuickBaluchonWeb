function getInputsValue(arrayId, trim = false) {
    if (!arrayId || !Array.isArray(arrayId))
        return -1

    if (typeof trim !== 'boolean')
        return -2

    let input;
    let values = [];
    for (let i = 0; i < arrayId.length; i++) {
        input = document.getElementById(arrayId[i]);
        console.log(input);
        if( input && (input.tagName === 'INPUT' || input.tagName === 'SELECT')) {
            values[arrayId[i]] = trim === false ? input.value : input.value.trim();
        } else
            return -3;
    }
    return values;
}

function login(jwt, location) {
    try {
        jwt = JSON.parse(jwt)
    } catch (e) {
        console.log(jwt, e);
        return e
    }

    if (!location) {
        location = 'login';
    }
    document.cookie = 'access_token=' + jwt.access_token;
    let redirect = window.location.href.replace(location, jwt.role);
    window.location.href = redirect;

}

function ajax(url, json, method, callback, error) {
    let request = new XMLHttpRequest();
    request.onreadystatechange = function() {
        if(request.readyState === 4) {
            if(request.status === 200)
                callback(request.response);
            else {
                console.log(request.response) ;
                // Error
                if( error ) error({ status : request.status, content: request.response  });
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

function updatePwd(role) {
    let ids = ['inputOldPassword', 'inputPassword'];
    let values = getInputsValue(ids, true);
    if( values < 0 ) // error codes
        return false;
    if( values['inputOldPassword'] && values['inputPassword'] ) {
        json = JSON.stringify( {
            oldpassword : values['inputOldPassword'],
            password : values['inputPassword']
        } );
        if( json.length > 2 ) {
            let idClient = getIdClient();
            if( idClient )
                ajax(`../api/${role}/` + idClient, json, 'PATCH', updated)
            else return false;
        }


    } else {
        console.log('Error params');
        return false;
    }
}

function language (l) {
    ajax(`/api/languages/${l}`, '', 'GET', reload, console.log) ;
}


function reload (text) {
    window.location.reload() ;
}

function signout () {
    let request = new XMLHttpRequest();
    request.onreadystatechange = function() {
        if(request.readyState === 4) {
            if(request.status === 200) {
                let url = JSON.parse(request.responseText) ;
                document.cookie = "access_token=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
                window.location.href = url;
            } else {
                // Error
                console.log(request.response);
            }
        }
    }
    request.open('GET', '../api/client/signout', true);
    request.send();
}

function getPackage () {
    let values = getInputsValue(['pkgInput'], true) ;
    let pkg = parseInt(values['pkgInput']) ;
    if (typeof(pkg) == "number")
        window.location.href = `/package/${pkg}` ;
}
