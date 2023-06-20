<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jobs\SingleTextJob;
use App\Jobs\BulkTextJob;
use App\Jobs\ImportTextJob;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\Sms;
use App\Models\SingleText;
use App\Models\SignupOtp;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Tymon\JWTAuth\JWTManager as JWT;
use JWTAuth;
use JWTFactory;
use Redirect;
use Illuminate\Support\Facades\Mail;
class SingleTextController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
 private function check_characters($stringLength,$phone){
        $string_length= Str::length($stringLength);
        if($string_length <= 160){
            return 1;
        }elseif(320 >= $string_length && $string_length > 160){
        return 2;

        }elseif(480 >= $string_length && $string_length >320){
            return 3;

        }elseif(640 >= $string_length && $string_length >480){

            return 4;
        }elseif(800 >= $string_length && $string_length >640){

            return 5;
        }elseif(960 >= $string_length  && $string_length >800){

            return 6;
        }else{
            return null;

        }

    }

    public function store(Request $request ,$id)
    {
        $input = $request->only(
            'text','phone','subject','no_of_phone'
     );
    
   
                             

       $validator = Validator::make($input, [
           'text' => 'required',
           'phone' => 'required|min:11',
           'subject' => 'required',
           'no_of_phone' => 'required|numeric',

           
       ]);

       if($validator->fails()){
           return response()->json(['success'=>false,"message"=>'input fails'],422);

       }

    //    return response()->json(['success'=>false,"message"=>' right']);

       $sms_api_key=env('SMS_API_KEY');
       $sms_secret_key=env('SMS_SECRET_KEY');
       $sms_sender_id=env('SMS_SENDER_ID');
       $acount = Sms::find($id);

          $num=$this->check_characters($request->text,$request->no_of_phone);

      if($acount->num_of_sms < $num){
        return response()->json(["success"=> false,"message"=>"Not enough balance! please buy a package"],422);

      }
      $job=(new SingleTextJob($sms_api_key,$sms_secret_key,$sms_sender_id,$input['phone'] ,$input['text'] ))
      ->delay(Carbon::now()->addSeconds(5));
      dispatch( $job);

       try {
        DB::beginTransaction();
    

        $acount = Sms::find($id);
        $acount->num_of_sms=  $acount->num_of_sms - $num;
        $acount->save();
        $singlesms = SingleText::create($input); // eloquent creation of data
            // $account->texts()->save($singlesms);
            $singlesms->sms()->associate($acount);
           $singlesms->save();
        //  $texts=  Sms::find($id)->texts;


        DB::commit();   
        return  response()->json(["success"=> true,"message"=>"Sms sent successfully"]);
    }
        catch (\Exception $e) {
        DB::rollback();  
        return response()->json(["error"=>$e],422);


        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SingleText  $singleText
     * @return \Illuminate\Http\Response
     */
    public function show(SingleText $singleText)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SingleText  $singleText
     * @return \Illuminate\Http\Response
     */
    public function edit(SingleText $singleText)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SingleText  $singleText
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SingleText $singleText)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SingleText  $singleText
     * @return \Illuminate\Http\Response
     */
    public function destroy(SingleText $singleText)
    {
        //
    }


    public function single_id(Request $request,$id)

    {

        $input = $request->only(
            'text','phone','subject','no_of_phone'
     );




       $validator = Validator::make($input, [
           'text' => 'required',
           'phone' => 'required|min:11',
           'subject' => 'required',
           'no_of_phone' => 'required|numeric',


       ]);

       if($validator->fails()){
           return response()->json(['success'=>false,"message"=>'input fails'],422);

       }

    //    return response()->json(['success'=>false,"message"=>' right']);

       $sms_api_key=env('SMS_API_KEY');
       $sms_secret_key=env('SMS_SECRET_KEY');
       $sms_sender_id=env('SMS_SENDER_ID');
       $acount = Sms::find($id);
   $num=$this->check_characters($request->text,$request->no_of_phone);
      if($acount->num_of_sms < $num){
        return response()->json(["success"=> false,"message"=>"Not enough balance! please buy a package"],422);

      }

      $job=(new SingleTextJob($sms_api_key,$sms_secret_key,$sms_sender_id,$input['phone'] ,$input['text'] ))
      ->delay(Carbon::now()->addSeconds(5));
      dispatch( $job);

       try {
        DB::beginTransaction();


        $acount = Sms::find($id);
        $acount->num_of_sms=  $acount->num_of_sms -  $num;
        $acount->save();
        $singlesms = SingleText::create($input); // eloquent creation of data
            // $account->texts()->save($singlesms);

            $singlesms->sms()->associate($acount);
           $singlesms->save();
        //  $texts=  Sms::find($id)->texts;


        DB::commit();
        return  response()->json(["success"=> true,"message"=>"Sms sent successfully"]);
    }
        catch (\Exception $e) {
        DB::rollback();
        return response()->json(["error"=>$e],422);


        }

    }
    public function single_Bearer(Request $request,$id)

    {

        $input = $request->only(
            'text','phone','subject','no_of_phone'
     );




       $validator = Validator::make($input, [
           'text' => 'required',
           'phone' => 'required|min:11',
           'subject' => 'required',
           'no_of_phone' => 'required|numeric',


       ]);

       if($validator->fails()){
           return response()->json(['success'=>false,"message"=>'input fails'],422);

       }

    //    return response()->json(['success'=>false,"message"=>' right']);

       $sms_api_key=env('SMS_API_KEY');
       $sms_secret_key=env('SMS_SECRET_KEY');
       $sms_sender_id=env('SMS_SENDER_ID');
       $acount = Sms::find($id);
   $num=$this->check_characters($request->text,$request->no_of_phone);
      if($acount->num_of_sms < $num){
        return response()->json(["success"=> false,"message"=>"Not enough balance! please buy a package"],422);

      }

      $job=(new SingleTextJob($sms_api_key,$sms_secret_key,$sms_sender_id,$input['phone'] ,$input['text'] ))
      ->delay(Carbon::now()->addSeconds(5));
      dispatch( $job);

       try {
        DB::beginTransaction();


        $acount = Sms::find($id);
        $acount->num_of_sms=  $acount->num_of_sms -  $num;
        $acount->save();
        $singlesms = SingleText::create($input); // eloquent creation of data
            // $account->texts()->save($singlesms);

            $singlesms->sms()->associate($acount);
           $singlesms->save();
        //  $texts=  Sms::find($id)->texts;


        DB::commit();
        return  response()->json(["success"=> true,"message"=>"Sms sent successfully"]);
    }
        catch (\Exception $e) {
        DB::rollback();
        return response()->json(["error"=>$e],422);


        }

    }
}
