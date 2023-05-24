<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SmsController;

Route::get('/send/sms/number/hello/mister/{number}/{message}', [SmsController::class,'sd_send_sms_api']);
Route::post('/sms/send/otp', [SmsController::class,'send_otp']);
Route::post('/sms/signup', [SmsController::class,'store']);
Route::post('/sms/forget/password', [SmsController::class,'forget_password']);
Route::post('/sms/reset/password', [SmsController::class,'reset_password']);
Route::post('/sms/login', [SmsController::class,'login']);
Route::post('/sms/add/balance/{id}', [SmsController::class,'add_balance'])->middleware('jwt.smsmiddleware');
Route::get('/sms/get/user/{id}', [SmsController::class,'get_user_details'])->middleware('jwt.smsmiddleware');
Route::get('/sms/get/all/from/user/', [SmsController::class,'all_user']);
Route::delete('/sms/delete/{id}', [SmsController::class,'delete']);
