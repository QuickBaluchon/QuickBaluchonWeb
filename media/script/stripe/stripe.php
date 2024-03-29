<?php
class Stripe
{

    private $api_key;


    public function __construct($api_key){
        $this->api_key = $api_key;
    }


    public function api(string $endPoint, array $data): stdClass{
        $ch = curl_init("https://api.stripe.com/v1/$endPoint");

        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_USERPWD => $this->api_key,
            CURLOPT_POSTFIELDS => http_build_query($data)
        ]);

        $response = json_decode(curl_exec($ch));


        return $response;
    }
}
