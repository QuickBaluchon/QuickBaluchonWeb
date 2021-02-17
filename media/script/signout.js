function signout () {
    let request = new XMLHttpRequest();
    request.onreadystatechange = function() {
        if(request.readyState === 4) {
            if(request.status === 200)
                window.location.href = "/";
             else {
                // Error
                console.log(request.response);
            }
        }
    }
    request.open('GET', '../api/client/signout', true);
    request.send();
    console.log('coucou');
}
