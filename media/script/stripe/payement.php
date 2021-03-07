<?php session_start();

$client ;
$token = $_POST['stripeToken'];
$name = $_POST['name'];
$email = $_POST['email'];
$price = $_POST['price'];

$price = $_SESSION["price"];


if(filter_var($email, FILTER_VALIDATE_EMAIL) && !empty($name) && !empty($token)){

    require("stripe.php");

    $stripe = new Stripe("sk_test_51IJfbbEPNKVbz8BscuWtY8qI5sN2TGUyqOvuNZkoxyAm6HFy6Cvv5a1kNrVjuQSlIcMX0ZfnAhYUrvgAV2ZjNIRJ00ROY7s52J");

    $client = $stripe->api('customers', [
        'source' => $token,
        'description'=> $name,
        'email' => $email
    ]);

    $charge = $stripe->api('charges', [
        'amount' => $price,
        'currency'=> "eur",
        'customer' => $client->id
    ]);
    header("Location:http://localhost:8888/QuickBaluchonWeb/client/bills");
}
