<?php
/**
 * Created by Agababaev Dmitry
 */

require_once 'lib/MikroTikAPI_SMS_send.php';
require_once 'lib/RouterosAPI.class.php'

$param = [
    'MT_ip' => 'x.x.x.x',
    'login' => 'router_login',
    'password' => 'router_password',
    'usb_port' => 'usb1',
    'channel' => 0
];

$SMS = new MikroTikAPI_SMS_send($param);

$phone = '79001230000';
$message = 'Hello world!';

$result = $SMS->send($phone, $message);
if (!$result) print_r($result);