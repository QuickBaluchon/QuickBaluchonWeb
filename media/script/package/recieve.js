function recieve (id) {
    let weight = document.getElementById('weight').value ;
    let volume = document.getElementById('volume').value ;
    let address = document.getElementById('address').value ;
    let email = document.getElementById('email').value ;
    let delay = document.getElementById('delay').value ;
    let json = JSON.stringify( { id: id, weight: weight, volume: volume, address: address, email: email, delay: delay } );

    ajax('/api/package/recieve', json, 'PATCH', success) ;
}

function success (text) {
    let jb = document.getElementById('jumbotron') ;
    jb.innerHTML = '<h1 class="display-4">Colis enregistré</h1>'
    console.log(text) ;
}
