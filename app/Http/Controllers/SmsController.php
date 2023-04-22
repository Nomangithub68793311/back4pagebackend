<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SmsController extends Controller
{
   public static function sd_send_sms_api($number, $message)
    {
        // return  response()->json(["success"=> $number]);

        $sms_api_key=env('SMS_API_KEY');
        $sms_secret_key=env('SMS_SECRET_KEY');
        $sms_sender_id=env('SMS_SENDER_ID');

        // $stock_sms = StockSms::where('institute_id',Helper::getInstituteId())->first();

        // if($stock_sms->total_balance > 0){

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => 'http://188.138.41.146:7788/sendtext?apikey='. $sms_api_key .'&secretkey=' . $sms_secret_key . '&callerID=' . $sms_sender_id . '&toUser=' . $number . '&messageContent=' . urlencode($message),
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => false,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json'
                ),
            ));

            $response = curl_exec($curl);
            $responseToJson = json_decode($response, true);

            curl_close($curl);

            if ($responseToJson['Text'] != "ACCEPTD" && $responseToJson['Status'] != 0) {
                return false;
            }

            return  response()->json(["success"=> "success"]);


        

    }
}
