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
