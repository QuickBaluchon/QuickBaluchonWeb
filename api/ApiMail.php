<?php
require_once('Api.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php');

class ApiMail extends Api {
    public function __construct ($email, $subject, $message) {
        var_dump(mail("letourneaunathan40@gmail.com", "test", "coucou c'est moi"));
    }
}
