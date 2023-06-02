<?php

namespace App\Http\Controllers;

use App\Models\SoldPackage;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\Sms;
use App\Models\SingleText;
use App\Models\SignupOtp;
use App\Models\SmsPackage;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Tymon\JWTAuth\JWTManager as JWT;
use JWTAuth;
use JWTFactory;
use Redirect;
use Illuminate\Support\Facades\Mail;
class SoldPackageController extends Controller
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
    public function store(Request $request,$id)
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
        

          $package = SmsPackage::find($request->package_id);
        //   $package = SmsPackage::where('id', $request->package_id)->get();
        //   return  response()->json(["success"=> $package[0]->name,"data"=>"Added successfully"]);

          $sold_package = SoldPackage :: create([
            
            'no_of_sms' => $package->no_of_sms,
            'amount' => $package->amount,
            'name' => $package->name,


          ]);
        $acount = Sms::find($id);
        $sold_package->sms()->associate($acount);
       $sold_package->save();

       $acount->num_of_sms= $acount->num_of_sms + $sold_package->no_of_sms;
       $acount->balance= $acount->balance + $sold_package->amount * 100;

             $acount->save();

            DB::commit();   
            return  response()->json(["success"=> true ,"data"=>"Added successfully"]);
        }
            catch (\Exception $e) {
            DB::rollback();  
            return response()->json(["error"=>$e],422);


            }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SoldPackage  $soldPackage
     * @return \Illuminate\Http\Response
     */
    public function show(SoldPackage $soldPackage)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SoldPackage  $soldPackage
     * @return \Illuminate\Http\Response
     */
    public function edit(SoldPackage $soldPackage)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SoldPackage  $soldPackage
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SoldPackage $soldPackage)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SoldPackage  $soldPackage
     * @return \Illuminate\Http\Response
     */
    public function destroy(SoldPackage $soldPackage)
    {
        //
    }
}
