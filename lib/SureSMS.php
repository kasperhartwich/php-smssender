<?php
/*
 * SureSMS.com
 * https://www.suresms.com
 */

require __DIR__ . '/SMSSender.php';

class SureSMS extends SMSSender
{
    public $api_url = 'https://suresms.com/Script/GlobalSendSMS.aspx';
    public $schema = array(
        'login' => 'username',
        'password' => 'password',
        'to' => 'recipient',
        'from' => 'sender',
        'Text' => 'message',
        'url' => 'callback_url',
    );

    public function __construct($username, $password, $log_file = false)
    {
        parent::__construct($username, $password, $log_file);
    }

    public function setRecipient($value) {
        return strlen($value)==8 ? '+45' . $value : $value;
    }
 
    public function translateResponse() {
        return substr($this->response, 0, 13)=="Message sent.";
    } 
}
