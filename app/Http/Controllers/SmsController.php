<?php

namespace App\Http\Controllers;

use App\Models\Sms;
use App\Models\SignupOtp;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Tymon\JWTAuth\JWTManager as JWT;
use JWTAuth;
use JWTFactory;
use Redirect;
use Illuminate\Support\Facades\Mail;
class SmsController extends Controller
{

    public function store(Request $request){
        $input = $request->only(
            'name','phone','password','otp'
     );
    
   
                             

       $validator = Validator::make($input, [
           'name' => 'required',
           'otp' => 'required',
           'phone' => 'required|min:11',
           'password' => 'required|min:8'
           
       ]);

       if($validator->fails()){
           return response()->json(['success'=>false,"message"=>'input fails'],422);

       }
      
    //    return response()->json(["success"=>"success"]);


       $matchThese = ['phone' => $request->phone];
       $found_with_phone=Sms::where($matchThese)->first();


       if($found_with_phone){
           return response()->json(['success'=>false, 'message' => 'Phone Number Exists'],422);

       }
       $signup_otp_with_phone=SignupOtp::where('phone', $input['phone'])->latest()->first();
       $signup_otp_with_otp=SignupOtp::where('otp', $input['otp'])->latest()->first();
       $now=now();
    //   $yes= $now->isAfter($signup_otp_with_phone->expire_at);
    //    return response()->json(["error"=> $now]);

       if($signup_otp_with_phone && $signup_otp_with_otp && ($signup_otp_with_phone == $signup_otp_with_otp) && $now->isBefore($signup_otp_with_phone->expire_at)){
        $input['hashed_password'] = Hash::make($input['password']); 

        try {
            DB::beginTransaction();
            $account =Sms::create($input); // eloquent creation of data
            if (!$account) {
                return response()->json(["error"=>"didnt work"],422);
            }
           
            SignupOtp::where('id', $signup_otp_with_phone->id)->delete();
            DB::commit();  
            $payload = JWTFactory::sub($account->id)
            // ->myCustomObject($account)
            ->make();
            $token = JWTAuth::encode($payload);
                return response()->json(['success'=>true, 
                'token' => '1'.$token ,
                'id' => $account->id ,
                        
            ]);
        }
        catch (\Exception $e) {
            DB::rollback();
            return response()->json(["error"=>"process error!"],422);
    }
       }
       if(!$signup_otp_with_phone ||  !$signup_otp_with_otp || !($signup_otp_with_phone == $signup_otp_with_otp)){
        return response()->json(['success'=>false,"message"=>"OTP not found"],422);

       }

       if($signup_otp_with_phone && $signup_otp_with_otp && ($signup_otp_with_phone == $signup_otp_with_otp) && !$now->isBefore($signup_otp_with_phone->expire_at)){
        return response()->json(['success'=>false,"message"=>"time expired"],422);

       }

      
    }
    public function send_otp(Request $request){
        $input = $request->only(
            'phone','type'
     );
     $validator = Validator::make($input, [
        'type' => 'required',
        'phone' => 'required'
        
    ]);

    if($validator->fails()){
        return response()->json(['success'=>false,"message"=>'input fails'],422);

    }
    $matchThese = ['phone' => $request->phone];
    $found_with_phone=Sms::where($matchThese)->first();

    if($found_with_phone){
        return response()->json(['success'=>false, 'message' => 'Phone Number Exists'],422);

    }
    $now=now();
    $input['otp'] = rand(123456,999999);
    $input['expire_at'] = $now->addMinutes(2);

    $message='Your SMS service signup OTP is ' .  $input['otp'];


    $sms_api_key=env('SMS_API_KEY');
    $sms_secret_key=env('SMS_SECRET_KEY');
    $sms_sender_id=env('SMS_SENDER_ID');

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://188.138.41.146:7788/sendtext?apikey='. $sms_api_key .'&secretkey=' . $sms_secret_key . '&callerID=' . $sms_sender_id . '&toUser=' .  $input['phone'] . '&messageContent=' . urlencode($message),
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
        try {
            DB::beginTransaction();
            $signupOtp =SignupOtp::create($input); // eloquent creation of data
            if (!$signupOtp) {
                return response()->json(['success'=>false,"message"=>'did not work'],422);
            }
            
           
            DB::commit();  
         return  response()->json(['success'=>true,"message"=>'successfully otp sent']);
        }
        catch (\Exception $e) {
            DB::rollback();
            return response()->json(['success'=>false,"message"=>"process error!"],422);
    }

    
    }
    public function login(Request $request){
        $input = $request->only(
           'phone','password'
     );
       $validator = Validator::make($input, [
         
           'phone' => 'required|min:11',
           'password' => 'required|min:8'
           
       ]);
       
       if($validator->fails()){
        return response()->json(['success'=>false,"message"=>'input fails'],422);

    }
       $matchThese = ['phone' => $request->phone];
       //   super admin
           $found_with_phone=Sms::where($matchThese)->first();
   
           if($found_with_phone){
               // $date1 = Carbon::parse($found->payment_date);
               // $now = Carbon::now();
               // $diff = $date1->diffInDays($now);
               // if($diff >30){
               //     return response()->json(["success"=>$false,"message"=>"you need to pay minthly fee" ]);
               // }
               if (!Hash::check($request->password, $found_with_phone->hashed_password)) {
                   return response()->json(['success'=>false, 'message' => 'Login Fail, please check password'],422);
                }
                $payload = JWTFactory::sub($found_with_phone->id)
                // ->myCustomObject($account)
                ->make();
                $token = JWTAuth::encode($payload);
                    return response()->json(['success'=>true, 
                    'token' => '1'.$token ,
                    'id' => $found_with_phone->id ,
                            
                ]);

            }
            return response()->json(['success'=>false, 'message' => 'not found'],422);

    }
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

    public static function add_balance(Request $request,$id){
        $input = $request->only(
            'balance'
      );
        $validator = Validator::make($input, [
          
           
            'balance' => 'required'
            
        ]);
        
        if($validator->fails()){
         return response()->json(['success'=>false,"message"=>'input fails'],422);
 
     }
     try {
        DB::beginTransaction();
    

        $sms = Sms::find($id); // eloquent creation of data

        if(!$sms){
            return response()->json(['success'=>false,"message"=>'not found'],422);

        }
        
        $sms->balance= $sms->balance + $request->balance * 100;
        $sms->save();
        
        // $response = Http::post('http://127.0.0.1:8000/v1/event', [
        //     "email"=>$student->email
            
        // ]);
        DB::commit();   
        // $job=(new StudentEmailJob( $student->email,$student->password, $school->institution_name,$school->logo,))
        // ->delay(Carbon::now()->addSeconds(5));
        // dispatch( $job);
        return  response()->json(['success'=>true,"message"=>'balance added successfully']);
    }
        catch (\Exception $e) {
        DB::rollback();  
        return response()->json(["error"=>"didnt work"],422);
        }

    }

    
    public static function send_sms(Request $request,$id){
        $input = $request->only(
            'balance'
      );
        $validator = Validator::make($input, [
          
           
            'balance' => 'required'
            
        ]);
        
        if($validator->fails()){
         return response()->json(['success'=>false,"message"=>'input fails'],422);
 
     }
     try {
        DB::beginTransaction();
    

        $sms = Sms::find($id); // eloquent creation of data

        if(!$sms){
            return response()->json(['success'=>false,"message"=>'not found'],422);

        }
        
        $sms->balance= $sms->balance + $request->balance * 100;
        $sms->save();
        
        // $response = Http::post('http://127.0.0.1:8000/v1/event', [
        //     "email"=>$student->email
            
        // ]);
        DB::commit();   
        // $job=(new StudentEmailJob( $student->email,$student->password, $school->institution_name,$school->logo,))
        // ->delay(Carbon::now()->addSeconds(5));
        // dispatch( $job);
        return  response()->json(['success'=>true,"message"=>'balance added successfully']);
    }
        catch (\Exception $e) {
        DB::rollback();  
        return response()->json(["error"=>"didnt work"],422);
        }

    }

    public static function get_user_details($id){
        try {
            DB::beginTransaction();
        
    
            $sms = Sms::find($id); // eloquent creation of data
    
            if(!$sms){
                return response()->json(['success'=>false,"message"=>'not found'],422);
    
            }
            
            
            
            // $response = Http::post('http://127.0.0.1:8000/v1/event', [
            //     "email"=>$student->email
                
            // ]);
            DB::commit();   
            // $job=(new StudentEmailJob( $student->email,$student->password, $school->institution_name,$school->logo,))
            // ->delay(Carbon::now()->addSeconds(5));
            // dispatch( $job);
            return  response()->json(['success'=>true,"detsils"=> $sms]);
        }
            catch (\Exception $e) {
            DB::rollback();  
            return response()->json(["error"=>"didnt work"],422);
            }
    }


public static function all_user(){

        try {
            DB::beginTransaction();
        
    
            $sms = Sms::all(); // eloquent creation of data
    
            if(!$sms){
                return response()->json(['success'=>false,"message"=>'not found'],422);
    
            }
            DB::commit();   
            return  response()->json(['success'=>true,"detsils"=> $sms]);
        }
            catch (\Exception $e) {
            DB::rollback();  
            return response()->json(["error"=>"didnt work"],422);
            }
    }

    public static function delete($id){

        try {
            DB::beginTransaction();
        
     Sms::find($id)->delete();
    
          
            DB::commit();   
            return  response()->json(['success'=>true,"message"=> "deleted successfully"]);
        }
            catch (\Exception $e) {
            DB::rollback();  
            return response()->json(["error"=>"didnt work"],422);
            }
    }


}
