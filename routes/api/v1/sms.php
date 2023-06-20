<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SmsController;
use App\Http\Controllers\SoldPackageController;

Route::get('/send/sms/number/hello/mister/{number}/{message}', [SmsController::class,'sd_send_sms_api']);
Route::post('/sms/send/otp', [SmsController::class,'send_otp']);
Route::post('/sms/signup', [SmsController::class,'store']);
Route::post('/sms/forget/password', [SmsController::class,'forget_password']);
Route::post('/sms/reset/password', [SmsController::class,'reset_password']);
Route::post('/sms/login', [SmsController::class,'login']);
Route::post('/sms/add/balance/{id}', [SoldPackageController::class,'store'])->middleware('jwt.smsmiddleware');
Route::get('/sms/get/user/single/contact/{id}', [SmsController::class,'get_user_details'])->middleware('jwt.smsmiddleware');
Route::get('/sms/get/all/from/user/', [SmsController::class,'all_user']);
Route::delete('/sms/delete/{id}', [SmsController::class,'delete']);
Route::get('/sms/get/user/contact/single/api/{id}', [SmsController::class,'get_user_details_id']);
Route::get('/sms/get/user/contact/single/api/auth/bearer', [SmsController::class,'get_user_details_bearer'])->middleware('jwt.checkmiddleware');

