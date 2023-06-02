<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SmsAdminController;

Route::post('/sms/admin/signup', [SmsAdminController::class,'store']);
Route::post('/sms/admin/login', [SmsAdminController::class,'login']);
Route::post('/sms/add/free_trial', [SmsAdminController::class,'add_free_trial']);

