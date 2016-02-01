<?php
/*
 * Compaya SMS Gateway
 * https://www.cpsms.dk
 */

require __DIR__ . '/SMSSender.php';

class CPSMS extends SMSSender
{
    public $api_url = 'http://www.cpsms.dk/sms/';
    public $schema = array(
        'username' => 'username',
        'password' => 'password',
        'recipient' => 'recipient',
        'from' => 'sender',
        'url' => 'callback_url',
        'message' => 'message',
        'utf8' => 1,
        'id' => 'id',
    );

    public function __construct($username, $password, $log_file = false)
    {
        parent::__construct($username, $password, $log_file);
    }

    public function setRecipient($value) {
        return strlen($value)==8 ? '+45' . $value : $value;
    }

    public static function callback($raw_response)
    {
        switch(intval($raw_response['status'])) { //Status
            case 1:
                $status = self::STATUS_SENT;
                break;
            case 2:
                $status = self::STATUS_REJECTED;
                break;
            case 4:
                $status = self::STATUS_QUEUED;
                break;
            case 8:
                $status = self::STATUS_ERROR;
                break;
            default:
                $status = self::STATUS_UNKNOWN;
        }

        return array(
            'id' => array_key_exists('id', $raw_response) ? $raw_response['id'] : null,
            'recipient' => $raw_response['to'],
            'status' => $status,
            'received_at' => time(),
            'raw' => $raw_response,
        );
    }
}
