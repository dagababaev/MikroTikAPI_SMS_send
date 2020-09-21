<?php
// ------------------------------------------------------------------------------
//  © Copyright (с) 2020
//  Author: Dmitri Agababaev, d.agababaev@duncat.net
//
//  Redistributions and use of source code, with or without modification, are
//  permitted that retain the above copyright notice
//
//  License: MIT
// ------------------------------------------------------------------------------

class MikroTikAPI_SMS_send
{
    private $ROSAPI;
    private $ip;
    private $login;
    private $password;
    private $usb_port;
    private $channel;

    /*
     * $param = [
     * 'MT_ip' => 'x.x.x.x',
     * 'login' => 'router_login',
     * 'password' => 'router_password',
     * 'usb_port' => 'usb1',
     * 'channel' => int
     * ]
     */

    public function __construct ($param)
    {
        $this->ROSAPI = new RouterosAPI();
        if (!$this->ROSAPI)
            return json_encode(["success" => false, "description" => "RouterOS API Class required!"]);

        $this->ip = $param['ip'];
        $this->login = $param['login'];
        $this->password = $param['password'];
        $this->usb_port = $param['usb_port'];
        $this->channel = $param['channel'];
    }

    public function send($phone, $message)
    {

        $ROSAPI = $this->ROSAPI;

        // if connected successfully - sending message
        if ($ROSAPI->connect($this->ip, $this->login, $this->password)) {
            // SMS send command
            $response = $ROSAPI->comm("/tool/sms/send", [
                "port" => $this->usb_port,
                "channel" => $this->channel,
                "phone-number" => $phone,
                "message" => $message
                ]);

            if (isset($response['!trap'])) {
                $error = json_encode(["success" => false, "message" => $response['!trap'][0]['message']]);
                $ROSAPI->disconnect();
                return $error;
            }
            $ROSAPI->disconnect();
        } else {
            $error = json_encode(["success" => false, "message" => "Can't connect to {$this->ip}"]);
            return $error;
        }
        return true;
    }
}
