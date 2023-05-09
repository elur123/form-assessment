<?php

require_once '../vendor/autoload.php';
require_once '../config/dotenv.php';

use SendinBlue\Client\Configuration;
use SendinBlue\Client\Api\TransactionalEmailsApi;
use SendinBlue\Client\Model\SendSmtpEmail;

class MailController {
    private $api_key;

    private $to = "";
    private $fullname = "";
    private $from = "";
    private $subject = "";
    private $body = "";

    public function __construct($fullname, $from, $subject, $body) {
       $this->api_key = $_ENV['SEND_IN_BLUE_KEY'];

       $this->to = $_ENV['EMAIL_TO'];
       $this->fullname = $fullname;
       $this->from = $from;
       $this->subject = $subject;
       $this->body = $body;
       
    }

    public function send()
    {
        $config = Configuration::getDefaultConfiguration()->setApiKey('api-key', $this->api_key);
        $apiInstance = new TransactionalEmailsApi(
            new \GuzzleHttp\Client(),
            $config
        );

        $sendSmtpEmail =new SendSmtpEmail([
            'to' => [['email' => $this->to]],
            'subject' => $this->subject,
            'htmlContent' => $this->body,
            'sender' => ['name' => $this->fullname, 'email' => $this->from],
        ]);

        try {
            $result = $apiInstance->sendTransacEmail($sendSmtpEmail);
            return true;
            // return true;
        } catch (Exception $e) {
            echo 'Exception when calling TransactionalEmailsApi->sendTransacEmail: ', $e->getMessage(), PHP_EOL;
            return false;
        }
    }
}