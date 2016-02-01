<?php
/*
 * SMSgateway.dk / Compaz A/S
 * https://www.smsgateway.dk
 */

require __DIR__ . '/SMSSender.php';

class SMSgateway extends SMSSender
{
    public $api_url = 'http://smschannel1.dk/sendsms/'; // alternative: http://smschannel2.dk/sendsms/
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

    public static function callback($raw_response)
    {
        $returndata = array_key_exists('returndata', $raw_response) ? explode(':', $raw_response['returndata']) : null;
        switch($raw_response['status']) { //Status
            case 'sent':
                $status = self::STATUS_SENT;
                break;
            case 'received':
                $status = self::STATUS_RECEIVED;
                break;
            case 'rejected':
                $status = self::STATUS_REJECTED;
                break;
            case 'queued':
                $status = self::STATUS_QUEUED;
                break;
            case 'error':
                $status = self::STATUS_ERROR;
                break;
            case 'buffered':
            default:
                $status = self::STATUS_UNKNOWN;
        }

        return array(
            'id' => $returndata ? $returndata[1] : null,
            'recipient' => $raw_response['to'],
            'status' => $status,
            'received_at' => $raw_response['receivetime'],
            'raw' => $raw_response,
        );
    }

    public function translateResponse() {
        $xml = new SimpleXMLElement($this->response);
        if($xml->succes) {
            return $xml->msgid;
        }
        return false;
    } 
}
