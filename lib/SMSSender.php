<?php
/*
 *  php-smssender
 *  http://github.com/kasperhartwich/php-smssender
 */

class SMSSender
{
    const STATUS_ERROR = 'error';
    const STATUS_QUEUED = 'queued';
    const STATUS_RECEIVED = 'received';
    const STATUS_REJECTED = 'rejected';
    const STATUS_SENT = 'sent';
    const STATUS_UNKNOWN = 'unknown';

    public $log_file;
    public $api_url = null;
    public $schema = array();
    public $request;
    public $response;
    public $replaces = array( //Illegal characters
        "/贸/" => "o"
    );

    /**
     * SMSSender constructor. Set up username and password.
     * @param $username
     * @param $password
     * @param bool|false $log_file
     */
    public function __construct($username, $password, $log_file = false)
    {
        $this->__set('username', $username);
        $this->__set('password', $password);
        $this->log_file = $log_file;
    }

    /**
     * Custom setter
     * @param $key
     * @param $value
     * @throws Exception
     */
    public function __set($key, $value)
    {
        if ($key=='message') {$value = preg_replace(array_keys($this->replaces), array_values($this->replaces), $value);}

        if (method_exists($this, 'set' . ucwords($key))) {
            $value = call_user_func(array($this, 'set' . ucwords($key)), $value);
        }

        if (!in_array($key, $this->schema)) {
            throw new Exception('Unknown parameter: ' . $key);
        }

        $provider_key = array_search($key, $this->schema);

        $this->schema[$provider_key] = $value;
    }

    /**
     * Log function
     * @param $message
     */
    public function log($message)
    {
        if ($this->log_file) {
            $message = date('c') . ' ' . var_export($message, true) . "\n";
            file_put_contents($this->log_file, $message, FILE_APPEND);
        }
    }

    /**
     * Send SMS / SMs dispatcher
     * @return bool
     * @throws Exception
     */
    public function send()
    {
        $context = stream_context_create(array('http' => array('header' => "Accept-Charset: UTF-8;")));
        $this->request = $this->api_url . '?' . http_build_query($this->schema);

        $this->response = file_get_contents($this->request, FALSE, $context);

        $this->log("Sms dispatched: " . print_r($this->request, true) . " / "  . print_r($this->response, true));

        return $this->translateResponse();
    }

    /**
     * Callback function, used to translate a callback response-
     * @param $get_request
     * @throws Exception
     */
    public static function callback($get_request)
    {
        throw new Exception('Callback not supported or implemented for gateway.');
    }

    /**
     * Translate the response from the smsgateway.
     * @return bool
     * @throws Exception
     */
    public function translateResponse()
    {
        if (empty($this->response)) {
            throw new Exception('There were a problem with sending the sms.');
        }
        if (stristr($this->response, 'succes')) {
            return true;
        }
        return false;
    }

}
