<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

class SmsController extends Controller
{

    public function sendSingleDestination($phone, $message)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://messaging-service.co.tz/api/sms/v1/text/single',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{"from":"Moinfo", "to":"' . $phone . '",  "text": "' . $message . '", "senderID": "Moinfo"}',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Basic aWt1bWk6MTU5MTIzU3dlZHku',
                'Content-Type: application/json',
                'Accept: application/json'
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }

    public function sendSms()
    {
        $this->sendSingleDestination('255652894205', 'CRONJOB');
    }
}
