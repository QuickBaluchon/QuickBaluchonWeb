<?php
class Stripe
{


    public function api(string $endPoint, array $data): stdClass{
        $ch = curl_init("https://api.stripe.com/v1/$endPoint");

        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_USERPWD => "sk_test_51IS82ODFRjdRBJC1cFxDAzP0fHFk5DiFM7rg8UBTmDRDk0yJzsUH1ZJI4OnmwFnGcFMtu05MVctku9720lt4U0gk00uEpWyB97",
            CURLOPT_POSTFIELDS => http_build_query($data)
        ]);

        $response = json_decode(curl_exec($ch));


        return $response;
    }
}
