<?php

require_once '../vendor/autoload.php';
require_once '../config/dotenv.php';

function validate_recaptcha($recaptcha_data)
{
    // define the secret key and response variable
    $secret = $_ENV['RECAPTCHA_SECRET'];
    $response = $recaptcha_data;

    // build the POST request to Google reCAPTCHA API
    $url = 'https://www.google.com/recaptcha/api/siteverify';
    $data = array(
        'secret' => $secret,
        'response' => $response
    );

    $options = array(
        'http' => array(
            'header' => "Content-Type: application/x-www-form-urlencoded\r\n",
            'method' => 'POST',
            'content' => http_build_query($data),
        ),
    );

    $context = stream_context_create($options);
    $result = file_get_contents($url, false, $context);

    // handle the response
    try {
        $resultJson = json_decode($result);
        if ($resultJson->success) {
            return true;
        } else {
            return false;
        }
    } catch (Exception $e) {
        echo 'Error '. $e;
        return false;
    }

   
}