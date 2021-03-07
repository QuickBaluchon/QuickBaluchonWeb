<?php session_start();

$client ;
$price;
$idPrice = $_POST["price"];
$token = $_POST['stripeToken'];
$name = $_POST['name'];
$email = $_POST['email'];

foreach ($_SESSION["price"] as $prices) {

    if(isset($prices["price$idPrice"]) && $prices["price$idPrice"] > 0)
        $price = $prices["price$idPrice"] * 100;
    else {
        break;
    }
}

if(filter_var($email, FILTER_VALIDATE_EMAIL) && !empty($name) && !empty($token)){

    require("stripe.php");

    $stripe = new Stripe("sk_test_51ISOVnHCj3DUNMZIV9OcypTkoQBFymO1t4W2IUOU3w7G7MVxsjzWWH7LgOfLQLU2e36VCWCCNawsVJpaHf1HRxSo00Bgx6LdA4");

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
