
    let form = document.getElementById("form")
    let divForm = document.getElementById('divForm')
    form.addEventListener('submit', payement);

    function payement(event) {
        let numberCard = document.getElementById('number').value
        let month = document.getElementById('exp_month').value
        let year = document.getElementById('exp_year').value
        let cvcCard = document.getElementById('cvc').value

        Stripe.setPublishableKey('pk_test_51ISOVnHCj3DUNMZI5ixlGlYeDs1Kh6EOipmhXbwz2wfBTH7wdLy2Ou6ozBjkp1UVs9hZ72gHy1965sVTIwZDfrbJ000ch4H21i');

        event.preventDefault();

        Stripe.card.createToken({
            number: numberCard,
            exp_month: month,
            exp_year: year,
            cvc: cvcCard
        }, function(status, response){
            if(response.error){
                console.log(response.error);
            }else {
                let price = document.createElement("input");
                price.setAttribute("type", "hidden");
                price.name = "price";
                price.value = parseInt(document.getElementsByTagName("button")[2].id);
                form.appendChild(price);
                let hiddenInput = document.createElement("input");
                hiddenInput.setAttribute("type", "hidden");
                hiddenInput.name = "stripeToken";
                hiddenInput.value = response.id
                form.appendChild(hiddenInput);
                patch();
                form.submit();
            }
        })

    }


    function patch(reponse) {
        let id = document.getElementsByTagName("button")[2].id;
        console.log(id);

        let json = JSON.stringify( {
            paid: 1,
        } );

        ajax('../../api/bill/' + id, json, 'PATCH', hello);

    }
function hello(response){

    console.log(response);
}
