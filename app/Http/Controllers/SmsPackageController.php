<?php

namespace App\Http\Controllers;

use App\Models\SmsPackage;
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
class SmsPackageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function get_all()
    {
        try {
            DB::beginTransaction();
        

            $smsPackages=SmsPackage::all();

            DB::commit();   
            return  response()->json(["success"=> true,"data"=>$smsPackages]);
        }
            catch (\Exception $e) {
            DB::rollback();  
            return response()->json(["error"=>"didnt work"],422);


            }
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
    public function store(Request $request)
    {
         $input = $request->only(
            'no_of_sms','amount' ,'name'
     );
    
   
                             

       $validator = Validator::make($input, [
           'no_of_sms' => 'required|numeric',
           'amount' => 'required|numeric',
           'name' => 'required'

          
           
       ]);

       if($validator->fails()){
           return response()->json(['success'=>false,"message"=>'input fails'],422);
       }

       $matchThese = ['no_of_sms' => $request->no_of_sms,'amount' => $request->amount,'name' => $request->name];
       $found_with_package=SmsPackage::where($matchThese)->first();


       if($found_with_package){
           return response()->json(['success'=>false, 'message' => 'Package Exists'],422);

       }

       try {
        DB::beginTransaction();
        $smsPackage =SmsPackage::create($input); // eloquent creation of data
        if (!$smsPackage) {
            return response()->json(['success'=>false,"message"=>'did not work'],422);
        }
        
       
        DB::commit();  
     return  response()->json(['success'=>true,"message"=>'smsPackage created successfully ']);
    }
    catch (\Exception $e) {
        DB::rollback();
        return response()->json(['success'=>false,"message"=>"process error!"],422);
}
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SmsPackage  $smsPackage
     * @return \Illuminate\Http\Response
     */
    public function show(SmsPackage $smsPackage)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SmsPackage  $smsPackage
     * @return \Illuminate\Http\Response
     */
    public function edit(SmsPackage $smsPackage)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SmsPackage  $smsPackage
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SmsPackage $smsPackage)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SmsPackage  $smsPackage
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id)
    { 

        $input = $request->only(
            'package_id'
     );


       $validator = Validator::make($input, [

           'package_id' => 'required'



       ]);

       if($validator->fails()){
           return response()->json(['success'=>false,"message"=>'input fails'],422);
       }
       try {
            DB::beginTransaction();
        

            SmsPackage::where('id', $request->package_id)->delete(); // eloquent creation of data
            $smsPackages=SmsPackage::all();

            DB::commit();   
            return  response()->json(["success"=> "deleted successfully","data"=>$smsPackages]);
        }
            catch (\Exception $e) {
            DB::rollback();  
            return response()->json(["error"=>"didnt work"],422);


            }
    
    }

    }

