<?php

namespace App\Http\Integration\Beem;
use App\Http\Integration\Beem\Constants;
use Illuminate\Support\Facades\Log;

class HttpHelper
{
    public function send($recipients, $message): bool
    {
       
        $api_key=env('BEEM_API_KEY');
        $secret_key=env('BEEM_SECRET_KEY');

        $postData = array(
            'source_addr' => env('BEEM_SENDER_ID'),
            'encoding' => 0,
            'schedule_time' => '',
            'message' => $message,
            'recipients' => $recipients
        );

        $Url = env('BEEM_API_URL');

        $ch = curl_init($Url);
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt_array($ch, array(
            CURLOPT_POST => TRUE,
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_HTTPHEADER => array(
                'Authorization:Basic ' . base64_encode("$api_key:$secret_key"),
                'Content-Type: application/json'
            ),
            CURLOPT_POSTFIELDS => json_encode($postData)
        ));

        $response = curl_exec($ch);

        if ($response === FALSE) {
            return false;
            Log::info("Error: " . curl_error($ch));
        }else{
            return true;
        }
    }


    
}
