<?php
require_once('Api.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php');

class ApiMail extends Api {
    private $_senderEmail = "grepo.sarah19@gmail.com";

    public function __construct ($subject, $email, $message) {
        $from = new SendGrid\Email(null, $this->_senderEmail);
        $to = new SendGrid\Email(null, $email);
        $content = new SendGrid\Content("text/html", $message);
        $mail = new SendGrid\Mail($from, $subject, $to, $content);

        $apiKey = getenv('SENDGRID_API_KEY');
        $sg = new \SendGrid($apiKey);

        $response = $sg->client->mail()->send()->post($mail);
        return $response ;
    }
}
