
    let form = document.getElementById("form")
    let divForm = document.getElementById('divForm')
    form.addEventListener('submit', payement);

    function payement(event) {
        let numberCard = document.getElementById('number').value
        let month = document.getElementById('exp_month').value
        let year = document.getElementById('exp_year').value
        let cvcCard = document.getElementById('cvc').value

        Stripe.setPublishableKey('pk_test_51IS82ODFRjdRBJC1O0PCA6C2Fo8X5H3iLhH7a27UZQMcMqx0M7zEhIXVVuSYFHM0UsAb10TaJVRv66ruZz6vffc500LmPvJYuK');

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
                let hiddenInput = document.createElement("input")
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
