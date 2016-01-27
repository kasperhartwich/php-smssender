<?php
/*
 * Inmobile
 * https://www.inmobile.dk
 */

require __DIR__ . '/SMSSender.php';

class Inmobile extends SMSSender
{
    public $url = 'https://mm.inmobile.dk/Api/V2/Get/SendMessages';
    public $schema = array(
        'apikey' => 'apikey',
        'recipients' => 'recipient',
        'sendername' => 'sender',
        'statuscallbackurl' => 'callback_url',
        'text' => 'message',
        'class' => 'flash',
    );

    public function __construct($apikey, $log_file = false)
    {
        $this->__set('apikey', $apikey);
        $this->flash = 'false';
        $this->callback_url = '';
        $this->log_file = $log_file;
    }

    public function setRecipient($value) {
        return strlen($value)==8 ? '+45' . $value : $value;
    }

    public function setFlash($value) {
        return $value ? 'true' : 'false';
    }

    public function translateResponse() {
        if (stristr($this->response, 'Error')) {
            return false;
        }
        list($reciepient, $id) = explode(':', $this->response);
        return $id;
    }
}
