<?php
/*
 * Compaya SMS Gateway
 * https://www.cpsms.dk
 */

require __DIR__ . '/SMSSender.php';

class CPSMS extends SMSSender
{
    public $url = 'http://www.cpsms.dk/sms/';
    public $schema = array(
        'username' => 'username',
        'password' => 'password',
        'recipient' => 'recipient',
        'from' => 'sender',
        'url' => 'callback_url',
        'message' => 'message',
        'utf8' => 1
    );

    public function __construct($username, $password, $log_file = false)
    {
        $this->callback_url = null;
        parent::__construct($username, $password, $log_file);
    }

}
