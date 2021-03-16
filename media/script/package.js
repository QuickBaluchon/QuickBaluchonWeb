function recieve (id) {
    let data = getInputsValue(['weight', 'address', 'email', 'delay'], true) ;
    let json = JSON.stringify( { weight: data['weight'], address: data['address'], email: data['email'], delay: data['delay'], status: 1 } );

    ajax(`/api/package/${id}`, json, 'PATCH', recieved) ;
}

function recieved (text) {
    console.log(text) ;
    let jb = document.getElementById('jumbotron') ;
    jb.innerHTML = '<h1 class="display-4">Colis enregistré</h1>' ;
}

function deliver (id) {
    let json = JSON.stringify( { status: 3 } );
    ajax(`/api/stop/${id}`, '', 'PATCH', delivered) ;
    ajax(`/api/package/${id}`, json, 'PATCH', delivered) ;
}

function delivered (text) {
    let jb = document.getElementById('jumbotron') ;
    jb.innerHTML = '<h1 class="display-4">Colis livré</h1>' ;
}

function absent (id) {
    ajax(`/api/package/${id}&fields=volume,warehouse`, '', 'GET', getVolume) ;
}

function getVolume (text) {
    let arr = JSON.parse(text) ;
    let json = JSON.stringify( { AvailableVolume: -arr['volume'] } ) ;
    ajax(`/api/warehouse/${arr['warehouse']}`, json, 'GET', absented) ;
}

function absented (text) {
    let jb = document.getElementById('jumbotron') ;
    jb.innerHTML = '<h1 class="display-4">Destinataire absent</h1>' ;
}

function stopDeliveries (id) {
    ajax(`/api/stop/${id}`, '', 'DELETE', ended) ;
}
function ended (text) {
    let jb = document.getElementById('jumbotron') ;
    jb.innerHTML = '<h1 class="display-4">Tournée arrêtée</h1>' ;
}