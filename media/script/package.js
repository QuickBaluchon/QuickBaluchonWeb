function recieve (id) {
    let weight = document.getElementById('weight').value ;
    let volume = document.getElementById('volume').value ;
    let address = document.getElementById('address').value ;
    let email = document.getElementById('email').value ;
    let delay = document.getElementById('delay').value ;
    let json = JSON.stringify( { weight: weight, volume: volume, address: address, email: email, delay: delay, status: 1 } );

    ajax(`/api/package/${id}`, json, 'PATCH', recieved) ;
}

function recieved (text) {
    let jb = document.getElementById('jumbotron') ;
    jb.innerHTML = '<h1 class="display-4">Colis enregistré</h1>' ;
}

function deliver (id) {
    let json = JSON.stringify( { status: 3 } );
    ajax(`/api/package/${id}`, json, 'PATCH', recieved) ;
}
