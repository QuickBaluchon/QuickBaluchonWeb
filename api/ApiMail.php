<?php
require_once('Api.php');

class ApiMail extends Api {
    public function __construct ($email, $subject, $message) {
        mail($email, $subject, $message);
    }
}
