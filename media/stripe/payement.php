<?php

$client ;
$token = $_POST['stripeToken'];
$name = $_POST['name'];
$email = $_POST['email'];

if(filter_var($email, FILTER_VALIDATE_EMAIL) && !empty($name) && !empty($token)){

    require("stripe.php");

    $stripe = new Stripe("sk_test_51IJfbbEPNKVbz8BscuWtY8qI5sN2TGUyqOvuNZkoxyAm6HFy6Cvv5a1kNrVjuQSlIcMX0ZfnAhYUrvgAV2ZjNIRJ00ROY7s52J");

    $client = $stripe->api('customers', [
        'source' => $token,
        'description'=> $name,
        'email' => $email
    ]);


    echo $client->id;



    $charge = $stripe->api('charges', [
        'amount' => 2000,
        'currency'=> "eur",
        'customer' => $client->id
    ]);

    echo "paiement réussi";

    die();
}
