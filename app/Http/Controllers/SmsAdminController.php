<?php

namespace App\Http\Controllers;

use App\Models\SmsAdmin;
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
class SmsAdminController extends Controller
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
   public function store(Request $request)
    {
        $input = $request->only(
            'email','password'
     );
    
   
                             

       $validator = Validator::make($input, [
           
           'email' => 'required',
           'password' => 'required|min:8'
           
       ]);

       if($validator->fails()){
           return response()->json(['success'=>false,"message"=>'input fails'],422);

       }
      
    //    return response()->json(["success"=>"success"]);


       $matchThese = ['email' => $request->email];
       $found_with_mail=SmsAdmin::where($matchThese)->first();


       if($found_with_mail){
           return response()->json(['success'=>false, 'message' => 'Email Exists'],422);

       }
     
  

        $input['hashed_password'] = Hash::make($input['password']); 
        // return response()->json(["success"=>$input['hashed_password']]);

        try {
            DB::beginTransaction();
            $user =SmsAdmin::create($input); // eloquent creation of data
            if (!$user) {
                return response()->json(["error"=>"didnt work"],422);
            }
           
            DB::commit();  

            $payload = JWTFactory::sub($user->id)
            // ->myCustomObject($account)
            ->make();

            $token = JWTAuth::encode($payload);
                return response()->json(['success'=>true, 
                'token' => '1'.$token ,
                'id' => $user->id ,
                        
            ]);
        }
        catch (\Exception $e) {
            DB::rollback();
            return response()->json(["error"=>$e],422);
    }
       
      

   }
    public function login(Request $request){
        $input = $request->only(
           'email','password'
     );
       $validator = Validator::make($input, [

           'email' => 'required',
           'password' => 'required|min:8'

       ]);

       if($validator->fails()){
        return response()->json(['success'=>false,"message"=>'input fails'],422);

    }
       $matchThese = ['email' => $request->email];
       //   super admin
           $found_with_email=SmsAdmin::where($matchThese)->first();

           if($found_with_email){

               if (!Hash::check($request->password, $found_with_email->hashed_password)) {
                   return response()->json(['success'=>false, 'message' => 'Login Fail, please check password'],422);
                }
                $payload = JWTFactory::sub($found_with_email->id)
                // ->myCustomObject($account)
                ->make();
                $token = JWTAuth::encode($payload);
                    return response()->json(['success'=>true,
                    'token' => '1'.$token ,
                    'id' => $found_with_email->id ,

                ]);

            }
            return response()->json(['success'=>false, 'message' => 'not found'],422);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SmsAdmin  $smsAdmin
     * @return \Illuminate\Http\Response
     */
    public function show(SmsAdmin $smsAdmin)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SmsAdmin  $smsAdmin
     * @return \Illuminate\Http\Response
     */
    public function edit(SmsAdmin $smsAdmin)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SmsAdmin  $smsAdmin
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SmsAdmin $smsAdmin)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SmsAdmin  $smsAdmin
     * @return \Illuminate\Http\Response
     */
    public function destroy(SmsAdmin $smsAdmin)
    {
        //
    }
}
