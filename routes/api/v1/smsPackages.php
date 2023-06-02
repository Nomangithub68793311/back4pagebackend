<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SmsPackageController;

Route::post('/sms/package/add/{id}', [SmsPackageController::class,'store'])->middleware('jwt.smsadminmiddleware');
Route::get('/sms/package/get', [SmsPackageController::class,'get_all']);
Route::delete('/sms/package/delete/{id}', [SmsPackageController::class,'destroy'])->middleware('jwt.smsadminmiddleware');

