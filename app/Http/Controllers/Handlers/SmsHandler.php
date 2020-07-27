<?php


namespace App\Http\Controllers\Handlers;


class SmsHandler
{
    function send_sms($mobile, $content)
    {
        $url = "https://esms.mimsms.com/smsapi";
        $data = [
            "api_key" => config('sms.sms_apikey'),
            "type" => "text",
            "contacts" => $mobile,
            "senderid" => config('sms.sms_sender_id'),
            "msg" => $content,
        ];
        error_log(json_encode($data));
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        error_log($response);
        curl_close($ch);
        return $response;
    }
}
