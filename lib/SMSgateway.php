<?php
/*
 * SMSgateway.dk / Compaz A/S
 * https://www.smsgateway.dk
 */

require __DIR__ . '/SMSSender.php';

class SMSgateway extends SMSSender
{
    public $url = 'http://smschannel1.dk/sendsms/'; // alternative: http://smschannel2.dk/sendsms/
    public $schema = array(
        'username' => 'username',
        'password' => 'password',
        'to' => 'recipient',
        'from' => 'sender',
        'message' => 'message',
        'class' => 'flash',
        'charset' => 'iso-8859-1'
    );

    public function __construct($username, $password, $log_file = false)
    {
        $this->flash = 0;
        parent::__construct($username, $password, $log_file);
    }

    public function setRecipient($value) {
        return strlen($value)==8 ? '+45' . $value : $value;
    }
 
    public function setMessage($value) {
        return urlencode(utf8_decode($value));
    }
 
    public function setFlash($value) {
        return $value ? 1 : 0;
    }

    public function translateResponse() {
        $xml = new SimpleXMLElement($this->response);
        if($xml->succes) {
            return $xml->msgid;
        }
        return false;
    } 
}
