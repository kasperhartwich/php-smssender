php-smssender
=============

This is my take on a sms sender interface, that in time should support a lot of different sms gateways.

These are at the moment supported:
- CPSMS
- SMSgateway.dk
- SureSMS.com


Example
-------

``` php
require 'lib/CPSMS.php';

$sms = new CPSMS('username', 'password');
$sms->recipient = '87654321';
$sms->sender = 'php-smssender';
$sms->message = 'Here comes the sun.';
$sms->send();
```