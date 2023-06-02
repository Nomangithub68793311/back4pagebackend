<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\Sms;
use App\Models\SignupOtp;
use App\Models\SingleText;
use App\Models\SoldPackage;
use App\Models\BulkText;
use App\Models\ImportText;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Tymon\JWTAuth\JWTManager as JWT;
use JWTAuth;
use JWTFactory;
use Redirect;
use Illuminate\Support\Facades\Mail;
class GetAllData extends Controller
{
    public function all_data()
    {
        try {
            DB::beginTransaction();
            // Post::whereDate('created_at', Carbon::today())->get();
          $single=  SingleText::whereDate('created_at', Carbon::today())->get();
          $from_contacts=ImportText::whereDate('created_at', Carbon::today())->get()->sum('no_of_sms');
          $from_file=BulkText::whereDate('created_at', Carbon::today())->get()->sum('no_of_sms');

          $single_month =    SingleText::whereMonth('created_at', Carbon::now()->month)->get();
          $from_contacts_month=    ImportText::whereMonth('created_at', Carbon::now()->month)->get()->sum('no_of_sms');
          $from_file_month=    BulkText::whereMonth('created_at', Carbon::now()->month)->get()->sum('no_of_sms');
  
          $single_total =    SingleText::all();
          $from_contacts_total=    ImportText::sum('no_of_sms');
          $from_file_total=    BulkText::sum('no_of_sms');
                
	  $total_user =    Sms::all()->count();
          $today_sell=SoldPackage::whereDate('created_at', Carbon::today())->get()->sum('amount');
          $monthly_sell=  SoldPackage::whereMonth('created_at', Carbon::now()->month)->get()->sum('amount');


            DB::commit();   
            return  response()->json(["success"=> true,
            "today_sent"=>$single->count()+$from_contacts+$from_file,
            "monthly_sent"=>$single_month->count()+$from_contacts_month+$from_file_month,
            "total_sent"=>$single_month->count()+$from_contacts_month+$from_file_month,
	    "total_user"=> $total_user,
	    "today_sell"=>$today_sell,
            "monthly_sell"=>$monthly_sell
            
        ]);
        }
            catch (\Exception $e) {
            DB::rollback();  
            return response()->json(["error"=>"didnt work"],422);


            }
    }
   
   
}
