
    let form = document.getElementById("form")
    let divForm = document.getElementById('divForm')
    form.addEventListener('submit', payement);




    function payement(event) {
        let numberCard = document.getElementById('number').value
        let month = document.getElementById('exp_month').value
        let year = document.getElementById('exp_year').value
        let cvcCard = document.getElementById('cvc').value

        Stripe.setPublishableKey('pk_test_51IJfbbEPNKVbz8BsMxzp6ArJX53Qu8tbWKoYCBIuOoA32vBrlZp5Bmty0JV6JLGOhEQNV7eASt6LB15GQ8JdOToj00GFtSiOaY');

        event.preventDefault();

        Stripe.card.createToken({
            number: numberCard,
            exp_month: month,
            exp_year: year,
            cvc: cvcCard
        }, function(status, response){
            if(response.error){
                let message = document.createElement('p');
                message.innerHTML = '' + response.error.message + ''
                divForm.appendChild(message);
            }else {
                let hiddenInput = document.createElement("input")
                hiddenInput.setAttribute("type", "hidden");
                hiddenInput.name = "stripeToken";
                hiddenInput.value = response.id
                form.appendChild(hiddenInput);
                form.submit();
            }
        })

    }
