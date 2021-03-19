<?php
require_once('Api.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php');

class ApiMail extends Api {
    public function __construct ($subject, $email, $message) {
        $from = new SendGrid\Email(null, "grepo.sarah19@gmail.com");
        $to = new SendGrid\Email(null, $email);
        $content = new SendGrid\Content("text/plain", $message);
        $mail = new SendGrid\Mail($from, $subject, $to, $content);

        $apiKey = getenv('SENDGRID_API_KEY');
        $sg = new \SendGrid($apiKey);


        $response = $sg->client->mail()->send()->post($mail);
        return $response ;
    }
}
