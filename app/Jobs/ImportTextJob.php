<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ImportTextJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
   public $api_key;
    public $secret_key;
    public $sms_sender_id;
    public $phone_number;
    public $text;
    public function __construct($api_key,$secret_key,$sms_sender_id,$phone_number,$text)
    {
        $this->api_key=$api_key;  
        $this->secret_key=$secret_key;
        $this->sms_sender_id=$sms_sender_id;
        $this->phone_number=$phone_number;  
        $this->text=$text;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach ( $this->phone_number as $phone){
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => 'http://188.138.41.146:7788/sendtext?apikey='. $this->api_key .'&secretkey=' . $this->secret_key . '&callerID=' . $this->sms_sender_id . '&toUser=' .  $phone . '&messageContent=' . urlencode($this->text),
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
              return response()->json(["success"=>false,"message"=>"Error! sms not sent"],422);
            }

        }
    }
}
